<?php

namespace App\Livewire\Customer;

use App\Livewire\MainBase;
use Livewire\Component;

class MyBooks extends MainBase
{
    public function render()
    {    $books = auth()->user()->userbooks()->with('book')->get();
        return view('livewire.customer.my-books',compact('books'))->layout('layouts.app');
    }
}
