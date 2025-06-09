<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Rental;
use App\Models\Book;
use App\Models\Payment;

class MidtransController extends Controller
{
public function callback(Request $request)
{
    $serverKey = config('midtrans.server_key');

    // Hitung signature hash untuk verifikasi keamanan
    $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

    if ($hashed === $request->signature_key) {
        // Ambil ID dari order_id seperti "RENTAL-123"
        $orderId = str_replace('RENTAL-', '', $request->order_id);

        // Cari rental dan payment berdasarkan relasi
        $rental = Rental::find($orderId);

        if (!$rental) {
            Log::warning("Rental tidak ditemukan: " . $orderId);
            return response()->json(['message' => 'Rental not found'], 404);
        }

        $payment = $rental->payment; // pastikan ada relasi 'payment' di model Rental

        if (in_array($request->transaction_status, ['settlement', 'capture'])) {
            $payment->status = 'paid';
            $payment->save();

            // Kurangi stok hanya sekali, jika rental belum aktif
            if ($rental->status !== 'active') {
                $book = $rental->book; // pastikan ada relasi 'book' di model Rental
                $book->decrement('stock', $rental->rentalItem->quantity); // pastikan ada relasi 'rentalItem'
                $rental->status = 'active';
                $rental->save();
            }
        }
    }

    return response()->json(['message' => 'Callback processed']);
}

}
