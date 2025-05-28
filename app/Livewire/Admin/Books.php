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

    public function render()
{
    $query = Book::with(['category', 'genres']);

    // if (!empty($this->selectedCategories)) {
    //     $query->whereIn('category_id', $this->selectedCategories);
    // }

    // if (!empty($this->selectedGenres)) {
    //     $query->whereIn('genre_id', $this->selectedGenres);
    // }

    // if ($this->search) {
    //     $query->where('title', 'like', '%' . $this->search . '%');
    // }

    $books = $query->paginate($this->perPage);
    // Ambil mapping genre_ids per book_id
    $bookGenreMap = BookGenreCustom::all()->keyBy('book_id');

    return view('livewire.admin.book', [
        'books' => $books,
        'categories' => $this->categories,
        'genres' => $this->genres,
        'bookGenreMap' => $bookGenreMap
    ]);
}
}