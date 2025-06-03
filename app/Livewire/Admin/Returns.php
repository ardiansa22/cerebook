<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Rental;
use Livewire\Component;

class Returns extends MainBase
{
    protected $casts = [
        'return_date' => 'datetime',
        'actual_return_date' => 'datetime', // Asumsi ini adalah kolom untuk 'returned_at'
    ];
    public function render()
    {
        $query = Rental::whereNotNull('actual_return_date')->with(['user', 'items', 'fine']);
        $returnedRentals = $query->paginate($this->perPage);
        return view('livewire.admin.returns',[
            'returnedRentals' => $returnedRentals
        ]);
    }
}
