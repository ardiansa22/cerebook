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
        $this->fields = [
            'id' => '',
        ];

        $this->searchableFields = [
        'id',
        'user.name',
        'items.book.title',
        'status',
        'return_date'
    ];
    }
    public function openReturnModal($id)
    {
        $this->selectedRental = Rental::with(['items.book', 'user'])->findOrFail($id);

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
                'after_or_equal:' . $this->selectedRental->rental_date,
                'before_or_equal:' . now()->format('Y-m-d'),
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

            $this->fineAmount = 0;
            foreach ($this->selectedRental->items as $item) {
                if ($item->book) {
                    $this->fineAmount += $this->lateDays * $item->book->fines_price;
                }
            }

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

        // Perbaikan di sini: izinkan status rented dan late
        if (!$rental || !in_array($rental->status, ['rented', 'late'])) {
            $this->dispatch('showNotification', [
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

        foreach ($rental->items as $item) {
            if ($item->book) {
                $finesPerDay = $item->book->fines_price ?? 0;
                $fineAmount += $lateDays * $finesPerDay;
            }
        }

        Fines::create([
            'rental_id' => $rental->id,
            'fine_amount' => $fineAmount,
            'days_late' => $lateDays,
            'payment_method' => $this->paymentMethod,
            'is_paid' => true,
        ]);
    }

        $path = $this->return_evidence->store('return_evidence', 'public');

        $rental->update([
            'status' => 'returned',
            'actual_return_date' => $actualReturnDate->toDateTimeString(),
            'return_evidence' => $path,
        ]);

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
        // Perbarui status ke "late" jika lewat return_date
        $lateRentals = Rental::where('status', 'rented')
            ->whereDate('return_date', '<', now())
            ->get();

        foreach ($lateRentals as $rental) {
            $rental->update(['status' => 'late']);
        }

        // Ambil data rented dan late yang sudah dibayar
        $query = Rental::with(['user', 'items.book', 'fine', 'payment'])
            ->whereIn('status', ['rented', 'late'])
            ->whereHas('payment', function ($q) {
                $q->where('status', 'paid');
            });

        if ($this->search) {
            $query->where(function ($q) {
                foreach ($this->searchableFields as $field) {
                    if (str_contains($field, '.')) {
                        $parts = explode('.', $field);
                        
                        if (count($parts) === 2) {
                            [$relation, $column] = $parts;

                            $q->orWhereHas($relation, function ($qr) use ($column) {
                                $qr->where($column, 'like', '%' . $this->search . '%');
                            });
                        } elseif (count($parts) === 3) {
                            [$relation1, $relation2, $column] = $parts;

                            $q->orWhereHas($relation1, function ($qr1) use ($relation2, $column) {
                                $qr1->whereHas($relation2, function ($qr2) use ($column) {
                                    $qr2->where($column, 'like', '%' . $this->search . '%');
                                });
                            });
                        }
                    } else {
                        $q->orWhere($field, 'like', '%' . $this->search . '%');
                    }
                }
            });
        }




        $loans = $query->paginate($this->perPage);

        return view('livewire.admin.loan', [
            'loans' => $loans,
        ]);
    }
}
    