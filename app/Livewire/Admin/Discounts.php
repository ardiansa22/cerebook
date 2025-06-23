<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Discount;
use Livewire\Component;

class Discounts extends MainBase
{
    public $books;
    public function mount()
    {
        $this->model = Discount::class;
        $this->fields = [
            'book_id' => '',
            'percentage' => '',
            'start_date' => '',
            'end_date' => '',

        ];
        $this->searchableFields = ['name'];
        $this->books = Book::all();
    }
    protected function getValidationRules()
    {
        return [
            'fields.start_date' => 'required|string|max:255',
        ];
    }
    public function render()
    {
       $query = Discount::with(['book']);
        $discounts = $query->paginate($this->perPage);
        return view('livewire.admin.discounts',[
            'discounts' => $discounts,
            'books' => $this->books,
        ]);
    }
}
