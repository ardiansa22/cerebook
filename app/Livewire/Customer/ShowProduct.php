<?php

namespace App\Livewire\Customer;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\Userbook;
use Livewire\Component;

class ShowProduct extends MainBase
{   public $book;

    public function mount(Book $book)
    {
        $this->book = $book;
    }

    public function buy()
    {
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'book_id' => $this->book->id,
            'status' => 1, // langsung paid
        ]);

        Userbook::create([
            'transaction_id' => $transaction->id,
            'book_id' => $this->book->id,
        ]);

        session()->flash('success', 'Book purchased successfully!');
        return redirect()->route('my-books');
    }
    public function render()
    {
        return view('livewire.customer.show-product')->layout('layouts.app');
    }
}
