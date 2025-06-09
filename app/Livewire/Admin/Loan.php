<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Fines;
use App\Models\Rental;
use Carbon\Carbon;
use Livewire\WithFileUploads;

class Loan extends MainBase
{
    use WithFileUploads;

     public $returnDate;
    public $proofImage;
    public $selectedRental;
    public $actualReturnDate;
    // Di dalam class Loan
    public $showFineDetails = false;
    public $fineAmount = 0;
    public $lateDays = 0;
    public $paymentMethod = 'cash'; // Default payment method


    public function mount()
    {
        $this->model = Rental::class;
        $this->searchableFields = ['id'];
    }


    public function openReturnModal($id)
    {
        $this->selectedRental = Rental::with(['book', 'user'])->findOrFail($id);
        $this->returnDate = now()->format('Y-m-d');
        $this->actualReturnDate = now()->format('Y-m-d');
        $this->proofImage = null;
        $this->showFineDetails = false;
        $this->fineAmount = 0;
        $this->lateDays = 0;
        $this->showModal();
    }

    public function getValidationRules()
    {
        return [
            'actualReturnDate' => 'required|date|after_or_equal:' . $this->selectedRental->actual_return_date,
        ];
    }
    public function calculateFine()
    {
        $this->validate(['actualReturnDate' => 'required|date']);
        
        $actualReturnDate = Carbon::parse($this->actualReturnDate);
        $estimatedReturnDate = Carbon::parse($this->selectedRental->return_date);

        if ($actualReturnDate->gt($estimatedReturnDate)) {
            $this->lateDays = $actualReturnDate->diffInDays($estimatedReturnDate);
            $this->fineAmount = $this->lateDays * 1000; // 1000 per hari
            $this->showFineDetails = true;
        } else {
            $this->showFineDetails = false;
            $this->fineAmount = 0;
            $this->lateDays = 0;
        }
    }

    public function processReturn()
{
    $this->validate($this->getValidationRules());

    $rental = $this->selectedRental;

    if (!$rental || $rental->status !== 'rented') {
        session()->flash('error', 'Pengembalian tidak valid.');
        return;
    }

    $actualReturnDate = Carbon::parse($this->actualReturnDate);
    $estimatedReturnDate = Carbon::parse($rental->return_date);

    $fineAmount = 0;
    if ($actualReturnDate->gt($estimatedReturnDate)) {
        $lateDays = $actualReturnDate->diffInDays($estimatedReturnDate);
        $fineAmount = $lateDays * 1000;

        Fines::create([
            'rental_id' => $rental->id,
            'fine_amount' => $fineAmount,
            'days_late' => $lateDays,
            'payment_method' => $this->paymentMethod, // Simpan metode pembayaran
            'is_paid' => true,
        ]);
    }
    // Update status & tanggal pengembalian
    $rental->update([
        'status' => 'returned',
        'actual_return_date' => $actualReturnDate->toDateTimeString(),
    ]);

    // Ambil semua rental items
    $rentalItems = $rental->items()->with('book')->get();

    foreach ($rentalItems as $item) {
        if ($item->book) {
            $item->book->increment('stock', $item->quantity);
        }
    }

    session()->flash('success', 'Buku berhasil dikembalikan.' .
        ($fineAmount > 0 ? ' Denda: Rp ' . number_format($fineAmount) : '')
    );

    $this->showModal = false;
}



   public function render()
{
    // Ambil semua rental yang statusnya masih "rented" dan sudah melewati return_date
    $lateRentals = Rental::where('status', 'rented')
        ->whereDate('return_date', '<', now())
        ->get();

    foreach ($lateRentals as $rental) {
        $rental->update(['status' => 'late']);
    }

    // Tampilkan semua data
    $query = Rental::with(['book', 'user', 'items', 'fine'])
            ->whereIn('status', ['rented', 'late']);
    $loans = $query->paginate($this->perPage);

    return view('livewire.admin.loan', [
        'loans' => $loans,
    ]);
}

}
