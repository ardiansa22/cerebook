<?php

namespace App\Livewire\Customer;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\Userbook;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ShowProduct extends MainBase
{   public $book;
    public $quantity = 1;
    public $showBuyModal = false;


    public function mount(Book $book)
    {
        $this->book = $book;
    }
    public function increment()
    {
        $this->quantity++;
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function OpenBuyModal()
    {
        $this->showBuyModal = true;
    }

    public function confirmBuy()
    {
        $this->buy(); // panggil method buy() setelah user klik Konfirmasi
    }

    public function buy()
    {
        DB::transaction(function () {
            $book = Book::where('id', $this->book->id)->lockForUpdate()->first();

            if ($book->stock < $this->quantity) {
                session()->flash('error', 'Stok tidak mencukupi!');
                return;
            }

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'book_id' => $book->id,
                'status' => 1,
            ]);

            for ($i = 0; $i < $this->quantity; $i++) {
                Userbook::create([
                    'transaction_id' => $transaction->id,
                    'book_id' => $book->id,
                ]);
            }

            $book->decrement('stock', $this->quantity);
        });

        session()->flash('success', 'Book purchased successfully!');
        return redirect()->route('my-books');
    }


    public function render()
    {
        return view('livewire.customer.show-product')->layout('layouts.app');
    }
}
