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
    public $selectedfilterGenre = null; // dari array menjadi single value
    public $selectedfilterCategory = null; // dari array menjadi single value

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
    public function getValidationRules()
{
    return [
        'fields.name' => 'required|string|max:255|unique:books,name,' . $this->editingId,
        'fields.title' => 'required|string|max:255',
        'fields.description' => 'nullable|string',
        'fields.price' => 'required|numeric|min:0',
        'fields.rent_price' => 'required|numeric|min:0',
        'fields.fines_price' => 'required|numeric|min:0',
        'fields.stock' => 'required|integer|min:0',
        'fields.category_id' => 'required|exists:categories,id',
        'selectedGenres' => 'required|array|min:1',
        'selectedGenres.*' => 'exists:genres,id',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Optional: jika upload gambar
    ];
}
    // Ubah method resetFilters
    public function resetFilters()
    {
        $this->reset(['selectedfilterGenre', 'selectedfilterCategory', 'search']);
        $this->resetPage();
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

    // Filter by category (single select)
    if ($this->selectedfilterCategory) {
        $query->where('category_id', $this->selectedfilterCategory);
    }

    // Filter by genre (single select)
    if ($this->selectedfilterGenre) {
        $query->whereHas('genres', function($q) {
            $q->where('genres.id', $this->selectedfilterGenre);
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