<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Payment;
use Livewire\Component;

class Payments extends MainBase
{
    public function render()
    {
        $query = Payment::with(['book','user']);
        $loans = $query->paginate($this->perPage);
        return view('livewire.admin.paymenet',[
            'loans' => $loans
        ]);
    }
}
