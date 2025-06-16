<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase; // Pastikan ini mengarah ke MainBase yang Anda miliki
use App\Models\Discount;
use App\Models\Book; // Import model Book

class Discounts extends MainBase
{
    // Properti untuk Autocomplete Buku
    public $searchResults = [];
    public $showBookDropdown = false;
    public $selectedBookName = ''; // Akan menyimpan nama buku yang ditampilkan di input

    public function mount()
    {
        $this->model = Discount::class; // Model yang digunakan oleh komponen ini
        $this->fields = [
            'book_id' => '',
            'percentage' => '',
            'start_date' => '',
            'end_date' => '',
            'is_active' => '',
        ];
        // Sangat PENTING: Kosongkan searchableFields di sini
        // Karena kita akan menangani pencarian buku (melalui relasi) secara manual di render()
        // agar tidak bentrok dengan logika getQuery di MainBase yang mencari di kolom langsung.
        $this->searchableFields = []; 
    }

    // Aturan Validasi untuk form diskon
    protected function getValidationRules()
    {
        return [
            'fields.book_id' => 'required|exists:books,id', // Memastikan book_id ada dan valid
            'fields.percentage' => 'required|numeric|min:0|max:100',
            'fields.start_date' => 'required|date',
            'fields.end_date' => 'required|date|after_or_equal:fields.start_date',
            'fields.is_active' => 'boolean',
        ];
    }

    // Dipanggil setiap kali input 'selectedBookName' (untuk autocomplete) berubah
    public function updatedSelectedBookName()
    {
        // Minimal 2 karakter untuk memulai pencarian, bisa disesuaikan
        if (strlen($this->selectedBookName) < 2) {
            $this->searchResults = [];
            $this->showBookDropdown = false;
            return;
        }

        // Lakukan pencarian buku di database
        $this->searchResults = Book::where('name', 'like', '%' . $this->selectedBookName . '%')
                                   ->limit(10) // Batasi hasil
                                   ->get()
                                   ->toArray(); // Konversi ke array untuk Livewire

        $this->showBookDropdown = count($this->searchResults) > 0;
    }

    // Dipanggil saat user memilih buku dari dropdown autocomplete
    public function selectBook($bookId, $bookName)
    {
        $this->fields['book_id'] = $bookId; // Simpan ID buku yang dipilih
        $this->selectedBookName = $bookName; // Tampilkan nama buku di input
        $this->searchResults = []; // Sembunyikan hasil pencarian
        $this->showBookDropdown = false; // Sembunyikan dropdown
    }

    // Override method resetInput dari MainBase untuk juga mereset properti autocomplete
    public function resetInput()
    {
        parent::resetInput(); // Panggil resetInput asli dari MainBase
        $this->selectedBookName = ''; // Reset input nama buku
        $this->searchResults = [];    // Kosongkan hasil pencarian
        $this->showBookDropdown = false; // Sembunyikan dropdown
    }

    // Override method fillForm dari MainBase untuk mengisi autocomplete saat mengedit diskon
    public function fillForm($id)
    {
        // Panggil fillForm asli dari MainBase untuk mengisi fields lainnya
        parent::fillForm($id); 

        $discount = Discount::with('book')->find($id);
        if ($discount && $discount->book) {
            $this->selectedBookName = $discount->book->name; // Isi input autocomplete dengan nama buku
        }
    }

    public function render()
    {
        // Panggil getQuery dari MainBase untuk mendapatkan base query (tanpa pencarian dari MainBase
        // karena searchableFields sudah dikosongkan)
        $query = $this->getQuery(Discount::class)->with(['book']);

        // Logika pencarian utama untuk tabel diskon berdasarkan NAMA BUKU
        // Ini akan memfilter diskon yang ditampilkan di tabel utama
        if ($this->search) {
            $query->whereHas('book', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $discounts = $query->paginate($this->perPage);

        return view('livewire.admin.discounts', [
            'discounts' => $discounts,
        ]);
    }
}