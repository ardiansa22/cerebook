<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\Userbook;
use Livewire\Component;

class Dashboard extends MainBase
{
    public function render()
    {
       $transactions = Transaction::all();
       $userbooks = Userbook::all();
        
        return view('dashboard', [
            'transactions' => $transactions,
            'userbooks' => $userbooks,
        ]);
    }
}
