<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Userbook;
use Livewire\Component;

class Mybook extends MainBase
{
    public function render()
    {
       $userbooks = Userbook::all();
        
        return view('livewire.admin.mybook', [
            'userbooks' => $userbooks
        ]);
    }
}
