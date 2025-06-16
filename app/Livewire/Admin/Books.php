<?php

namespace App\Livewire\Admin;

use App\Models\Book;
use App\Models\Category;
use App\Livewire\MainBase;
use App\Models\BookGenreCustom;
use App\Models\Genre;
use Livewire\WithPagination;

class Books extends MainBase
{
    use WithPagination;
    
    public $image;
    public $oldImage;
    public $uploadDirectory = 'books';
    public $searchableFields = ['name', 'title', 'description'];
    
    // Properti untuk many-to-many genres
    public $selectedGenres = [];
    public $genres;
    public $categories;

    // filer
    public $selectedfilterGenres = [];
    public $selectedfilterCategories = [];

    public $filtercategories = [];
    public $filtergenres = [];

    public function mount()
    {
         $this->model = Book::class;
         $this->fields = [
        'name' => '',
        'title' => '',
        'description' => '',
        'price' => '',
        'rent_price' => '',
        'fines_price' => '',
        'stock' => '',
        'category_id' => '',
        'image' => '',
        'selectedGenres' => [] // Tambahkan ini

    ];
        $this->categories = Category::where('is_active', true)->get();
        $this->genres = Genre::all(); // Load semua genre

        $this->filtercategories = Category::all();
        $this->filtergenres = Genre::all();
    }
    // Di dalam class Books
    public function resetFilters()
    {
        $this->reset(['selectedfilterGenres', 'selectedfilterCategories', 'search']);
        $this->resetPage(); // Reset pagination ke halaman 1
    }
    public function updatedFields($value, $key)
    {
        if ($key === 'price' && is_numeric($value)) {
            // Hitung rent_price (35% dari harga buku)
            $rentPrice = round($value * 0.35, 2);
            $this->fields['rent_price'] = $rentPrice;

            // Hitung fines_price (150% dari harga sewa per hari)
            $finesPrice = round($rentPrice * 1.5, 2);
            $this->fields['fines_price'] = $finesPrice;
        }
    }




    public function render()
    {
        $query = Book::with(['category', 'genres']);

        // Filter by categories
        if (!empty($this->selectedfilterCategories)) {
            $query->whereIn('category_id', $this->selectedfilterCategories);
        }

        // Filter by genres
        if (!empty($this->selectedfilterGenres)) {
            $query->whereHas('genres', function($q) {
                $q->whereIn('genres.id', $this->selectedfilterGenres);
            });
        }

        // Search
        if ($this->search) {
            $query->where(function($q) {
                foreach ($this->searchableFields as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        $books = $query->paginate($this->perPage);
        
        return view('livewire.admin.book', [
            'books' => $books,
            'categories' => $this->categories,
            'genres' => $this->genres,
            'bookGenreMap' => BookGenreCustom::all()->keyBy('book_id')
        ]);
    }
}