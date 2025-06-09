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
        // Verifikasi signature dari Midtrans
        $hashed = hash('sha512', 
            $request->order_id . 
            $request->status_code . 
            $request->gross_amount . 
            config('midtrans.server_key')
        );
        
        if ($hashed != $request->signature_key) {
            throw new \Exception('Invalid signature key');
        }

        // Ekstrak rental_id dari order_id (format: RENTAL-{id})
        $rentalId = str_replace('RENTAL-', '', $request->order_id);
        
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
                    
                    // Tidak perlu update status rental karena default sudah 'rented'
                    // Tapi bisa tambahkan logika jika perlu
                    
                    // Kurangi stok buku
                    $book = Book::findOrFail($rental->book_id);
                    $book->decrement('stock', $rental->items->first()->quantity);
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
    }
}