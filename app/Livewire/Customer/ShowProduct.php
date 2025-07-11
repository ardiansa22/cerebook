<?php

namespace App\Livewire\Customer;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowProduct extends MainBase
{
    public $book;
    public $rental_date;
    public $return_date;
    public $quantity = 1;
    public $showRentalModal = false;
    public $paymentMethod = 'transfer';
    public $totalPrice = 0;

    public function mount($book)
    {
        $this->book = Book::with('discounts')->findOrFail($book);
        $this->rental_date = Carbon::today()->format('Y-m-d');
        $this->return_date = Carbon::today()->addDays(1)->format('Y-m-d');
        $this->calculateTotal();
    }

    public function increment()
    {
        $this->quantity++;
        $this->calculateTotal();
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->calculateTotal();
        }
    }

    public function updatedRentalDate()
    {
        $this->calculateTotal();
    }

    public function updatedReturnDate()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $start = Carbon::parse($this->rental_date);
        $end = Carbon::parse($this->return_date);

        $days = max($start->diffInDays($end), 1);
        $this->totalPrice = $this->book->finalprice * $this->quantity * $days;
    }

    public function OpenRentalModal()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
            'rental_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:rental_date',
        ]);

        if ($this->quantity > $this->book->stock) {
            $this->dispatch('swal:stok', [
                'title' => 'Stok Tidak Cukup!',
                'text' => 'Jumlah yang dipilih melebihi stok buku yang tersedia.',
            ]);
            return;
        }

        $this->showRentalModal = true;
    }

    public function confirmRental()
    {
        $this->rental();
    }

    public function rental()
    {
        try {
            $rental = null;

            DB::transaction(function () use (&$rental) {
                $book = Book::where('id', $this->book->id)->lockForUpdate()->first();

                if (!$book || $book->stock < $this->quantity) {
                    throw new \Exception('Stok tidak mencukupi!');
                }

                $rental = Rental::create([
                    'user_id' => Auth::id(),
                    'rental_date' => $this->rental_date,
                    'return_date' => $this->return_date,
                    'status' => 'rented',
                    'total_price' => $this->totalPrice,
                ]);

                RentalItem::create([
                    'rental_id' => $rental->id,
                    'book_id' => $book->id,
                    'quantity' => $this->quantity,
                    'sub_total' => $this->totalPrice,
                ]);

                Payment::create([
                    'rental_id' => $rental->id,
                    'payment_date' => now(),
                    'amount' => $this->totalPrice,
                    'method' => $this->paymentMethod,
                    'status' => 'pending',
                ]);
            });

            // Midtrans config
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'RENTBOOK-' . $rental->id,
                    'gross_amount' => $this->totalPrice,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()?->name ?? 'Guest',
                    'email' => Auth::user()?->email ?? 'guest@example.com',
                    'phone' => Auth::user()?->phone ?? '0811111111',
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            Payment::where('rental_id', $rental->id)->update([
                'snap_token' => $snapToken // tambahkan ini, pastikan kolomnya ada
            ]);

            $this->dispatch('midtrans:pay', [
                'snapToken' => $snapToken
            ]);

            $this->showRentalModal = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyewa buku: ' . $e->getMessage());
        }
    }

    public function addToCart()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1|max:' . $this->book->stock,
            'rental_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:rental_date',
        ]);

        $exists = Cart::where('user_id', Auth::id())
            ->where('book_id', $this->book->id)
            ->where('rental_date', $this->rental_date)
            ->where('return_date', $this->return_date)
            ->first();

        if ($exists) {
            $exists->quantity += $this->quantity;
            $exists->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'book_id' => $this->book->id,
                'quantity' => $this->quantity,
                'rental_date' => $this->rental_date,
                'return_date' => $this->return_date,
            ]);
        }

        $this->dispatch('swal:success', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Buku berhasil ditambahkan ke keranjang!',
        ]);

        $this->showRentalModal = false;
    }

    public function removeFromCart($id)
    {
        Cart::find($id)?->delete();
    }

    public function render()
    {
        return view('livewire.customer.show-product')->layout('layouts.app');
    }
}
