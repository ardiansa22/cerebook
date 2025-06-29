<?php

namespace App\Livewire;

use Livewire\Component;

class BookSearch extends Component
{
    public $query = '';

    public function search()
    {
        // Redirect ke halaman pencarian dengan query sebagai parameter
        return redirect()->route('search', ['query' => $this->query]);
    }
    public function goToResult()
{
    if (strlen($this->query) >= 2) {
        return redirect()->route('search', ['query' => $this->query]);
    }
}


    public function render()
{
    $books = [];

    if (strlen($this->query) >= 2) {
        $books = Book::where('title', 'like', '%' . $this->query . '%')
                     ->limit(10)
                     ->get();
    }

    return view('livewire.book-search', [
        'books' => $books,
    ]);
}

}
