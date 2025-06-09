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
    // Konfigurasi Midtrans
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    $notif = new \Midtrans\Notification();

    $orderId = str_replace('RENTAL-', '', $notif->order_id);
    $transactionStatus = $notif->transaction_status;

    $payment = Payment::whereHas('rental', function ($q) use ($orderId) {
        $q->where('id', $orderId);
    })->first();

    if (!$payment) {
        Log::error("Pembayaran tidak ditemukan untuk order ID: $orderId");
        return response()->json(['message' => 'Not found'], 404);
    }

    if ($transactionStatus == 'settlement') {
        $payment->status = 'paid';
        $payment->save();

        // Kurangi stok buku
        $rental = $payment->rental;
        $book = $rental->book;
        $book->decrement('stock', $rental->rentalItem->quantity); // pastikan relasi rentalItem benar

        $rental->status = 'active';
        $rental->save();
    }

    return response()->json(['message' => 'OK']);
}
}
