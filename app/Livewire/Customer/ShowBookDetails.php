<?php

namespace App\Livewire\Customer;

use App\Livewire\MainBase;
use App\Models\Payment;
use App\Models\Rental;
use Livewire\Component;

class ShowBookDetails extends MainBase
{
    public $rental;

    public function mount($id)
    {
        $this->rental = Rental::with(['items.book', 'payment'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
    }
    public function continuePayment($rentalId)
    {
        $payment = Payment::where('rental_id', $rentalId)->first();

        if (!$payment || !$payment->snap_token) {
            session()->flash('error', 'Transaksi tidak ditemukan atau token tidak tersedia.');
            return;
        }

        $this->dispatch('midtrans:pay', [
            'snapToken' => $payment->snap_token
        ]);
    }

    public function render()
    {
        return view('livewire.customer.show-book-details')->layout('layouts.app');
    }
}
