<?php

namespace App\Livewire\Customer;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Payment;
use App\Models\Fines;
use Carbon\Carbon;
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
        $this->return_date = Carbon::today()->addDays(7)->format('Y-m-d');
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
        DB::transaction(function () {
            $book = Book::where('id', $this->book->id)->lockForUpdate()->first();

            if ($book->stock < $this->quantity) {
                session()->flash('error', 'Stok tidak mencukupi!');
                return;
            }

            // Create rental
            $rental = Rental::create([
                'user_id' => auth()->id(),
                'book_id' => $book->id,
                'rental_date' => $this->rental_date,
                'return_date' => $this->return_date,
                'status' => 'rented',
                'total_price' => $this->totalPrice,
            ]);

            // Create rental item
            RentalItem::create([
                'rental_id' => $rental->id,
                'book_id' => $book->id,
                'quantity' => $this->quantity,
                'sub_total' => $this->totalPrice,
            ]);

            // Create payment
            Payment::create([
                'rental_id' => $rental->id,
                'payment_date' => now(),
                'amount' => $this->totalPrice,
                'method' => $this->paymentMethod,
                'status' => 'paid',
            ]);

            // Update book stock
            $book->decrement('stock', $this->quantity);
        });

        session()->flash('success', 'Buku berhasil disewa!');
        return redirect()->route('my-books');
    }

    public function render()
    {
        return view('livewire.customer.show-product')->layout('layouts.app');
    }
}