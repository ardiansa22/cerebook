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
    public function mount()
    {
        $this->model = Rental::class;
        $this->fields = [
        'user_id' => '',
        'status' => '',
        'book_id' => '',
        'rental_date' => '',
        'return_date' => '',
        'total_price' => '',
        'actual_return_date' =>'',
        'return_evidence' => ''

        ];
        $this->searchableFields = ['name'];
    }
    public function render()
    {
        $query = Rental::whereNotNull('actual_return_date')->with(['user', 'items', 'fine']);
        $returnedRentals = $query->paginate($this->perPage);
        return view('livewire.admin.returns',[
            'returnedRentals' => $returnedRentals
        ]);
    }
}
