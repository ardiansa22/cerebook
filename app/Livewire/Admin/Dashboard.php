<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Rental;

class Dashboard extends MainBase
{
    public function render()
    {
        $totalBookProducts = Book::count();

        // Ambil data rental dengan status rented atau late yang sudah dibayar
       $loans = Rental::with(['items.book', 'user', 'fine', 'payment'])
        ->whereIn('status', ['rented', 'late'])
        ->whereHas('payment', function ($q) {
            $q->where('status', 'paid');
        })
        ->latest() // urutkan berdasarkan created_at descending
        ->paginate($this->perPage);



        return view('dashboard', [
            'totalBookProducts' => $totalBookProducts,
            'loans' => $loans,
        ]);
    }
}
