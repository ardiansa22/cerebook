<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Discount;
use Livewire\Component;
use Carbon\Carbon; // Penting: Import Carbon

class Discounts extends MainBase
{
    public $books;
    // Kita tidak perlu $selectedBookName lagi jika dropdown di mode edit tetap menampilkan semua buku.
    // Namun, jika Anda ingin dropdown dinonaktifkan di mode edit, kita bisa gunakan ini.
    // Untuk saat ini, asumsikan dropdown akan selalu menampilkan semua buku saat edit
    // atau hanya teks di mode edit. Mari kita buat lebih fleksibel.

    public function mount()
    {
        $this->model = Discount::class;
        $this->fields = [
            'book_id' => '',
            'percentage' => '',
            'start_date' => '',
            'end_date' => '',
          
        ];
        $this->searchableFields = ['percentage']; // Sesuaikan jika ingin mencari berdasarkan nama buku
        $this->books = Book::all(); // Load semua buku untuk pertama kali
    }
    // Pastikan Anda memanggil ini setelah start_date atau end_date diubah
    public function updatedFieldsStartDate()
    {
        $this->filterBooksAvailableForNewDiscount();
    }

    public function updatedFieldsEndDate()
    {
        $this->filterBooksAvailableForNewDiscount();
    }

    /**
     * Override getValidationRules() dari MainBase untuk validasi spesifik diskon.
     */
    // app/Livewire/Admin/Discounts.php

protected function getValidationRules()
{
    return [
        'fields.percentage' => 'required|numeric|min:1|max:100',
        'fields.start_date' => 'required|date',
        'fields.end_date' => 'required|date|after_or_equal:fields.start_date',
        'fields.book_id' => [
            'required',
            'exists:books,id',
            function ($attribute, $value, $fail) {
                $startDate = Carbon::parse($this->fields['start_date']);
                $endDate = Carbon::parse($this->fields['end_date']);

                $existingDiscount = Discount::where('book_id', $value)
                    ->where(function ($query) use ($startDate, $endDate) {
                        $query->where(function ($q) use ($startDate, $endDate) {
                            // Kasus 1: Rentang yang ada tumpang tindih dengan rentang baru
                            // start_date existing antara start_date baru dan end_date baru
                            // ATAU end_date existing antara start_date baru dan end_date baru
                            $q->whereBetween('start_date', [$startDate, $endDate])
                                ->orWhereBetween('end_date', [$startDate, $endDate]);
                        })->orWhere(function ($q) use ($startDate, $endDate) {
                            // Kasus 2: Rentang baru di dalam rentang yang ada
                            // start_date existing lebih awal dari start_date baru DAN end_date existing lebih lambat dari end_date baru
                            $q->where('start_date', '<', $startDate)
                                ->where('end_date', '>', $endDate);
                        });
                        // Kasus 3: Rentang yang ada di dalam rentang baru (tidak perlu penambahan logika karena sudah tercover oleh kasus 1)
                        // start_date baru lebih awal dari start_date existing DAN end_date baru lebih lambat dari end_date existing
                        // Ini sudah tercover oleh logika whereBetween jika Anda menggunakan BETWEEN (startDate, endDate)
                        // Jika Anda ingin lebih eksplisit, Anda bisa menambahkannya, tetapi umumnya tidak perlu.
                    })
                    ->when($this->editingId, function ($query) {
                        // Saat mengedit, abaikan diskon yang sedang diedit agar tidak memvalidasi diri sendiri
                        $query->where('id', '!=', $this->editingId);
                    })
                    ->first();

                if ($existingDiscount) {
                    $fail('Diskon untuk buku ini sudah ada dalam rentang tanggal tersebut.');
                }
            },
        ],
    ];
}

    public function render()
    {
        $query = Discount::with(['book']);

        // Mengganti cara pencarian agar bisa mencari berdasarkan nama buku
        if ($this->search) {
            $query->whereHas('book', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->orWhere('percentage', 'like', '%' . $this->search . '%'); // Biarkan jika ingin mencari % juga
        }

        $discounts = $query->paginate($this->perPage);

        return view('livewire.admin.discounts', [
            'discounts' => $discounts,
            'books' => $this->books, // Gunakan daftar buku yang selalu dimuat di mount/openCreateModal
        ]);
    }

    /**
     * Override openCreateModal() dari MainBase
     * untuk memfilter buku yang tersedia di dropdown.
     */
    public function openCreateModal()
{
    $this->resetInput();
    $this->modalTitle = 'Tambah Diskon Baru';
    $this->isEdit = false;
    $this->showModal = true;

    // Tampilkan semua buku saat membuat diskon baru
    $this->books = Book::all();
}

    /**
     * Override openEditModal() dari MainBase
     * untuk memastikan buku yang diedit selalu tersedia di dropdown.
     */
    public function openEditModal($id)
    {
        parent::openEditModal($id); // Panggil parent method untuk mengisi $this->fields dll.

        $record = $this->model::findOrFail($id);
        $this->modalTitle = 'Edit Diskon';

        // Untuk mode edit, tampilkan semua buku, atau setidaknya buku yang sedang diedit
        // agar pengguna dapat melihat nama buku aslinya meskipun tidak bisa mengubahnya
        $this->books = Book::all(); // Tampilkan semua buku saat edit
    }

    /**
     * Override save() dari MainBase
     * untuk menambahkan logika filtering buku setelah save.
     */
    public function save()
    {
        try {
            $this->validate($this->getValidationRules());

            if ($this->isEdit) {
                parent::update($this->editingId);
            } else {
                parent::store();
            }

            $this->showModal = false;
            // Panggil filterBooksAvailableForNewDiscount untuk memastikan daftar buku yang diperbarui
            $this->filterBooksAvailableForNewDiscount(); // Ini akan memuat semua buku jika tanggal tidak diisi lagi
            $this->showNotification($this->editingId ? 'Diskon berhasil diperbarui!' : 'Diskon berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showNotification('Gagal menyimpan diskon. Periksa kembali input Anda.', 'error');
            $this->showModal = true;
        } catch (\Throwable $e) {
            $this->showNotification('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            $this->showModal = true;
        }
    }

    /**
     * Metode pembantu untuk memfilter buku yang tersedia untuk diskon baru.
     * Dipanggil setelah save atau saat openCreateModal.
     */
    protected function filterBooksAvailableForNewDiscount()
    {
        // Hanya filter jika start_date dan end_date sudah terisi
        if (!empty($this->fields['start_date']) && !empty($this->fields['end_date'])) {
            $startDate = Carbon::parse($this->fields['start_date']);
            $endDate = Carbon::parse($this->fields['end_date']);

            $this->books = Book::whereDoesntHave('discounts', function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Kasus 1: Rentang yang ada tumpang tindih dengan rentang baru
                    $q->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate]);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Kasus 2: Rentang baru di dalam rentang yang ada
                    $q->where('start_date', '<', $startDate)
                        ->where('end_date', '>', $endDate);
                });
                // Saat mengedit, kecualikan diskon yang sedang diedit (jika diperlukan untuk pre-filtering)
                // Namun, untuk dropdown 'buat baru', ini tidak relevan karena belum ada editingId
            })->get();
        } else {
            $this->books = Book::all(); // Tampilkan semua jika tanggal belum dipilih
        }
    }



    // Tidak perlu override toggleStatus atau delete karena tidak ada validasi spesifik diskon di sana.
}