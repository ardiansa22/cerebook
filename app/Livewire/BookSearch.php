<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;

class BookSearch extends Component
{
    public $query = ''; // Ubah dari string $query menjadi $query saja

    public function render()
    {
        $books = [];

        if (strlen($this->query) >= 2) {
            $books = Book::where('title', 'like', '%' . $this->query . '%')
                        ->limit(10) // Batasi hasil untuk performa
                        ->get();
        }

        return view('livewire.book-search', [
            'books' => $books
        ]);
    }
}