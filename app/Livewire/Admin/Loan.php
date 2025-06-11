<?php

namespace App\Livewire\Admin;

use App\Livewire\MainBase;
use App\Models\Fines;
use App\Models\Payment;
use App\Models\Rental;
use Carbon\Carbon;
use Livewire\WithFileUploads;

class Loan extends MainBase
{
    use WithFileUploads;

    public $returnDate;
    public $return_evidence;
    public $selectedRental;
    public $actualReturnDate;
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
        $this->return_evidence = null;
        $this->showFineDetails = false;
        $this->fineAmount = 0;
        $this->lateDays = 0;
        $this->showModal();
    }

public function getValidationRules()
{
    return [
        'actualReturnDate' => [
            'required',
            'date',
            'after_or_equal:' . $this->selectedRental->rental_date, // tidak boleh sebelum tanggal pinjam
            'before_or_equal:' . now()->format('Y-m-d'), // tidak boleh lebih dari hari ini
        ],
        'return_evidence' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ];
}


    public function calculateFine()
    {
        $this->validate(['actualReturnDate' => 'required|date']);

        $actualReturnDate = Carbon::parse($this->actualReturnDate);
        $estimatedReturnDate = Carbon::parse($this->selectedRental->return_date);

        if ($actualReturnDate->gt($estimatedReturnDate)) {
            $this->lateDays = $actualReturnDate->diffInDays($estimatedReturnDate);
            $this->fineAmount = $this->lateDays * 1000;
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
        $this->dispatchBrowserEvent('showNotification', [
            'type' => 'error',
            'message' => 'Pengembalian tidak valid.'
        ]);
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
            'payment_method' => $this->paymentMethod,
            'is_paid' => true,
        ]);
    }

    // Simpan bukti pengembalian
    $path = $this->return_evidence->store('return_evidence', 'public');

    // Update data rental
    $rental->update([
        'status' => 'returned',
        'actual_return_date' => $actualReturnDate->toDateTimeString(),
        'return_evidence' => $path,
    ]);

    // Kembalikan stok buku
    $rentalItems = $rental->items()->with('book')->get();
    foreach ($rentalItems as $item) {
        if ($item->book) {
            $item->book->increment('stock', $item->quantity);
        }
    }

    $message = 'Buku berhasil dikembalikan.';
    if ($fineAmount > 0) {
        $message .= ' Denda: Rp ' . number_format($fineAmount);
    }

     $this->dispatch('swal:success', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => $message,
        ]);

    $this->showModal = false;
}


    public function render()
    {
        // Update status ke "late" jika sudah melewati return_date
        $lateRentals = Rental::where('status', 'rented')
            ->whereDate('return_date', '<', now())
            ->get();

        foreach ($lateRentals as $rental) {
            $rental->update(['status' => 'late']);
        }

        // Ambil data rental yang masih rented atau late dan sudah dibayar
        $query = Rental::with(['book', 'user', 'items', 'fine', 'payment'])
            ->whereIn('status', ['rented', 'late'])
            ->whereHas('payment', function ($q) {
                $q->where('status', 'paid');
            });

        $loans = $query->paginate($this->perPage);

        return view('livewire.admin.loan', [
            'loans' => $loans,
        ]);
    }
}
