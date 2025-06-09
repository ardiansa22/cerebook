<?php

namespace App\Livewire\Customer;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Payment;
use App\Models\Fines;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowProduct extends MainBase
{
    public $book;
    public $rental_date;
    public $return_date;
    public $quantity = 1;
    public $showRentalModal = false;
    public $paymentMethod = 'transfer';
    public $totalPrice = 0;

    public function mount(Book $book)
    {
        $this->book = $book;
        $this->rental_date = Carbon::today()->format('Y-m-d');
        $this->return_date = Carbon::today()->addDays(1)->format('Y-m-d');
        $this->calculateTotal();
    }

    public function increment()
    {
        if ($this->quantity < $this->book->stock) {
            $this->quantity++;
            $this->calculateTotal();
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $this->totalPrice = $this->book->price * $this->quantity;
    }

    public function OpenRentalModal()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1|max:' . $this->book->stock,
            'rental_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:rental_date',
        ]);

        $this->showRentalModal = true;
    }

    public function confirmRental()
    {
        $this->rental();
    }

   public function rental()
{
    try {
        // Variabel untuk menyimpan objek rental
        $rental = null;

        DB::transaction(function () use (&$rental) {
            // Ambil data buku secara eksklusif
            $book = Book::where('id', $this->book->id)->lockForUpdate()->first();

            if (!$book) {
                throw new \Exception('Buku tidak ditemukan!');
            }

            if ($book->stock < $this->quantity) {
                throw new \Exception('Stok tidak mencukupi!');
            }

            // Buat data rental
            $rental = Rental::create([
                'user_id' => auth()->id(),
                'book_id' => $book->id,
                'rental_date' => $this->rental_date,
                'return_date' => $this->return_date,
                'status' => 'rented',
                'total_price' => $this->totalPrice,
            ]);

            // Simpan detail rental item
            RentalItem::create([
                'rental_id' => $rental->id,
                'book_id' => $book->id,
                'quantity' => $this->quantity,
                'sub_total' => $this->totalPrice,
            ]);

            // Simpan data pembayaran awal
            Payment::create([
                'rental_id' => $rental->id,
                'payment_date' => now(),
                'amount' => $this->totalPrice,
                'method' => $this->paymentMethod,
                'status' => 'pending',
            ]);

            // Kurangi stok buku
            $book->decrement('stock', $this->quantity);
        });

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Buat parameter pembayaran Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => 'RENTAL-' . $rental->id,
                'gross_amount' => $this->totalPrice,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone ?? '0811111111',
            ],
        ];

        // Ambil SnapToken dari Midtrans
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Kirim event ke frontend untuk trigger Midtrans
        $this->dispatch('midtrans:pay', [
            'snapToken' => $snapToken
        ]);

        $this->showRentalModal = false;
        return redirect()->back();

    } catch (\Exception $e) {
        // Tangani error
        session()->flash('error', 'Gagal menyewa buku: ' . $e->getMessage());
        return redirect()->back();
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