<?php

namespace App\Livewire\Customer;

use App\Livewire\MainBase;
use App\Models\Category;
use Livewire\Component;

class ExploreBook extends MainBase
{   public $categori;
    public $books;

    public function mount(Category $categori)
    {
        $this->categori = $categori;
        $this->books = $categori->books()->latest()->get(); // pastikan relasi sudah benar
    }
    public function render()
    {
        return view('livewire.customer.explore-book')->layout('layouts.app');
    }
}
