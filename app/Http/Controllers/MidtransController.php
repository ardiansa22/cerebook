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
{
    try {
        Log::info('Midtrans Callback Received:', $request->all());
        
        // Verifikasi signature
        $hashed = hash('sha512', 
            $request->order_id . 
            $request->status_code . 
            $request->gross_amount . 
            config('midtrans.server_key')
        );
        
        if ($hashed != $request->signature_key) {
            Log::error('Invalid signature key');
            throw new \Exception('Invalid signature key');
        }

        Log::info('Signature verified');
        
        // Ekstrak rental_id
        $rentalId = str_replace('test2-', '', $request->order_id);
        Log::info('Extracted Rental ID:', ['rentalId' => $rentalId]);
        
        DB::transaction(function () use ($request, $rentalId) {
            $rental = Rental::with('items')->findOrFail($rentalId);
            Log::info('Found Rental:', ['rental' => $rental]);
            
            switch ($request->transaction_status) {
                case 'capture':
                case 'success':
                case 'settlement':
                    Log::info('Payment successful');
                    Payment::where('rental_id', $rental->id)
                        ->update([
                            'status' => 'paid',
                            'payment_date' => now(),
                        ]);
                    
                    // Update rental status
                    $rental->update(['status' => 'confirmed']);
                    
                    // Kurangi stok
                    $book = Book::findOrFail($rental->book_id);
                    $book->decrement('stock', $rental->items->first()->quantity);
                    Log::info('Stock decremented');
                    break;
                    
                case 'deny':
                case 'expire':
                case 'cancel':
                    Log::info('Payment failed');
                    Payment::where('rental_id', $rental->id)
                        ->update([
                            'status' => 'failed',
                        ]);
                    
                    $rental->update(['status' => 'cancelled']);
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
