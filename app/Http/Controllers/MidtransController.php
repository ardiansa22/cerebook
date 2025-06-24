<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Penting untuk logging
use Illuminate\Support\Facades\DB;   // Penting untuk transaksi database
use App\Models\Rental;
use App\Models\Book;
use App\Models\Payment;
use App\Models\RentalItem; // Pastikan ini di-import juga

class MidtransController extends Controller
{
    /**
     * Handle Midtrans payment callback notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {try {
        Log::info('Midtrans Callback Received:', $request->all());

        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $serverKey = config('midtrans.server_key');

        $stringToHash = $orderId . $statusCode . $grossAmount . $serverKey;
        $hashed = hash('sha512', $stringToHash);

        Log::info('Calculated Hash Input:', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'server_key_used' => $serverKey, // Ini untuk debugging, jangan tampilkan di production
            'string_to_hash' => $stringToHash,
            'calculated_hash' => $hashed,
            'midtrans_signature' => $request->signature_key,
        ]);

        if ($hashed != $request->signature_key) {
            Log::error('Invalid signature key!', [
                'calculated_hash' => $hashed,
                'midtrans_signature' => $request->signature_key,
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'gross_amount' => $grossAmount,
                'server_key_used' => $serverKey,
            ]);
            throw new \Exception('Invalid signature key');
        }

        // Ekstrak rental_id dari order_id (format: RENTAL-{id})
        $rentalId = str_replace('testing-', '', $request->order_id);
        
        DB::transaction(function () use ($request, $rentalId) {
            // Temukan rental
            $rental = Rental::with('items')->findOrFail($rentalId);
            
            // Update berdasarkan status transaksi Midtrans
            switch ($request->transaction_status) {
                case 'capture':
                case 'settlement':
                    // Pembayaran berhasil
                    Payment::where('rental_id', $rental->id)
                        ->update([
                            'status' => 'paid',
                            'payment_date' => now(),
                        ]);
                    


                    foreach ($rental->items as $item) {
                        Book::where('id', $item->book_id)->decrement('stock', $item->quantity);
                    }
                    break;
                    
                case 'deny':
                case 'expire':
                case 'cancel':
                    // Pembayaran gagal
                    Payment::where('rental_id', $rental->id)
                        ->update([
                            'status' => 'failed',
                        ]);
                    
                    // Update status rental menjadi cancelled
                    $rental->update(['status' => 'cancelled']);
                    break;
                    
                case 'pending':
                    // Pembayaran pending - tidak perlu action karena default payment status sudah pending
                    break;
            }
        });
        
        return response()->json(['status' => 'success']);
        
    } catch (\Exception $e) {
        Log::error('Midtrans callback error: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
    }}
