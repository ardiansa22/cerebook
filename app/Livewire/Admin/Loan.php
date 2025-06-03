<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Rental;
use Livewire\Component;

class Loan extends MainBase
{
    
    public function render()
    {
        $query = Rental::with(['book','user','items','fine']);
        $loans = $query->paginate($this->perPage);
        return view('livewire.admin.loan',[
            'loans' => $loans
        ]);
    }
}
