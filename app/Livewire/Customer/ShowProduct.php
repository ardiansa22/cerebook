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

    public function mount(Book $book)
    {
        $this->book = $book;
    }

public function buy()
{
    DB::transaction(function () {
        $book = Book::where('id', $this->book->id)->lockForUpdate()->first();

        if ($book->stock <= 0) {
            session()->flash('error', 'Stok buku habis!');
            return;
        }

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'status' => 1,
        ]);

        Userbook::create([
            'transaction_id' => $transaction->id,
            'book_id' => $book->id,
        ]);

        $book->decrement('stock');
    });

    session()->flash('success', 'Book purchased successfully!');
    return redirect()->route('my-books');
}

    public function render()
    {
        return view('livewire.customer.show-product')->layout('layouts.app');
    }
}
