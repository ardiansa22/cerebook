<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Rental;
use App\Models\Book;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
public function callback(Request $request)
{
    dd($request);
    $serverKey = config('midtrans.server_key');
    $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

    if ($hashed !== $request->signature_key) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    // Ambil ID dari "RENTAL-123"
    $rentalId = (int) str_replace('RENTAL-', '', $request->order_id);

    $rental = Rental::with(['payment', 'rentalItem', 'book'])->find($rentalId);

    if (!$rental) {
        return response()->json(['message' => 'Rental not found'], 404);
    }
    if ($rental->payment->status === 'paid') {
    return response()->json(['message' => 'Already processed']);
}


    if (in_array($request->transaction_status, ['settlement', 'capture'])) {
        DB::transaction(function () use ($rental) {
            // Update status pembayaran
            $rental->payment->update(['status' => 'paid']);

            // Cek apakah rental sudah aktif (hindari stok terpotong dua kali)
            if ($rental->status !== 'active') {
                // Kurangi stok buku
                $rental->book->decrement('stock', $rental->rentalItem->quantity);

                // Ubah status rental
                $rental->update(['status' => 'active']);
            }
        });
    }

    return response()->json(['message' => 'Callback processed']);
}

}
