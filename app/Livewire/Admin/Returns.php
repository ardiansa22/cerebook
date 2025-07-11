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
        $this->searchableFields = [
        'id',
        'user.name',
        'items.book.title',
        'status',
        'return_date'
    ];
    }
    public function render()
    {
        
        $query = Rental::whereNotNull('actual_return_date')->with(['user', 'items', 'fine']);
        if ($this->search) {
    $query->where(function ($q) {
        foreach ($this->searchableFields as $field) {
            if (str_contains($field, '.')) {
                $parts = explode('.', $field);
                if (count($parts) === 2) {
                    [$relation, $column] = $parts;
                    $q->orWhereHas($relation, function ($qr) use ($column) {
                        $qr->where($column, 'like', '%' . $this->search . '%');
                    });
                } elseif (count($parts) === 3) {
                    [$relation1, $relation2, $column] = $parts;
                    $q->orWhereHas($relation1, function ($qr1) use ($relation2, $column) {
                        $qr1->whereHas($relation2, function ($qr2) use ($column) {
                            $qr2->where($column, 'like', '%' . $this->search . '%');
                        });
                    });
                }
            } else {
                $q->orWhere($field, 'like', '%' . $this->search . '%');
            }
        }
    });
}

        $returnedRentals = $query->paginate($this->perPage);

        
        return view('livewire.admin.returns',[
            'returnedRentals' => $returnedRentals
        ]);
    }
}
