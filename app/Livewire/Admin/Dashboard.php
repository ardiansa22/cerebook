<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Userbook;
use Livewire\Component;

class Dashboard extends MainBase
{
    public function render()
    {
        $transactions = Transaction::paginate(10); // tambahkan pagination jika diperlukan

        $totalBookProducts = Book::all()->count(); // sesuaikan nama kolom 'type' jika berbeda
        $totalNonBookProducts = Product::all()->count(); // sesuaikan juga
        $totalTransactions = Transaction::count();

        return view('dashboard', [
            'transactions' => $transactions,
            'totalBookProducts' => $totalBookProducts,
            'totalNonBookProducts' => $totalNonBookProducts,
            'totalTransactions' => $totalTransactions,
        ]);
    }

}
