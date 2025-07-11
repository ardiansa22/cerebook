<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;

class BookSearch extends Component
{
    public $query = '';
    public $books = [];

    // Dipanggil otomatis setiap kali $query berubah
    public function updatedQuery()
    {   
        if (strlen($this->query) >= 2) {
            $this->books = Book::where('title', 'like', '%' . $this->query . '%')
                ->limit(10)
                ->get();
        } else {
            $this->books = [];
        }
    }

    public function goToResult()
    {
        if (strlen($this->query) >= 2) {
            return redirect()->route('search', ['query' => $this->query]);
        }
    }

    public function render()
    {
        return view('livewire.book-search');
    }
}
