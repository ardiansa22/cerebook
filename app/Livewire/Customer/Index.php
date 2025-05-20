<?php

namespace App\Livewire\Customer;

use App\Models\Book;
use Livewire\Component;


class Index extends Component
{
    public function render()
    {
        $books = Book::all();
        return view('livewire.customer.index',[
            'books' => $books,
        ])->layout('layouts.app');
    }
    
}
