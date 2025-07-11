<?php

namespace App\Livewire\Customer;

use App\Livewire\MainBase;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\RentalItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CartComponent extends MainBase
{
    public $book_id;
    public $quantity = 1;
    public $rental_date;
    public $return_date;
    public $paymentMethod = 'transfer'; // Tambahkan ini
    public $selectedItems = []; // untuk menyimpan ID cart yang dipilih user
    public $quantities = [];
    public $cartItems = [];
    public $showRentalModal = false;
    


    public function mount()
    {
        // Hapus otomatis item dengan rental_date di masa lalu
        Cart::where('user_id', Auth::id())
            ->whereDate('rental_date', '<', now())
            ->delete();
        if (Cart::where('user_id', Auth::id())->whereDate('rental_date', '<', now())->exists()) {
            Cart::where('user_id', Auth::id())
                ->whereDate('rental_date', '<', now())
                ->delete();

            session()->flash('message', 'Beberapa item dengan tanggal sewa yang telah lewat telah dihapus dari keranjang.');
        }

        // Ambil ulang data cart yang valid
        $this->cartItems = Cart::with(['book' => function ($query) {
            $query->select('id', 'title', 'image');
        }])->where('user_id', Auth::id())->get();

        // Inisialisasi quantities
        $this->quantities = $this->cartItems->pluck('quantity', 'id')->toArray();
    }



    public function updateQuantity($itemId, $newQuantity)
    {
        $item = Cart::find($itemId);
        if ($item && $newQuantity > 0) {
            $item->quantity = $newQuantity;
            $item->save();
            $this->mount(); // agar keranjang diperbarui di tampilan
            $this->dispatch('swal:success', [
                        'icon' => 'success',
                        'title' => 'Berhasil!',
                        'text' => 'Jumlah berhasil diperbarui.',
                    ]);
        }
    }

        public function OpenRentalModal()
{
    
    if (empty($this->selectedItems)) {
        session()->flash('error', 'Pilih minimal satu item untuk checkout.');
        return;
    }
    
    // Cek stok untuk semua item yang dipilih
    $items = Cart::whereIn('id', $this->selectedItems)
        ->where('user_id', Auth::id())
        ->with('book')
        ->get();
        
    foreach ($items as $item) {
        if ($item->quantity > $item->book->stock) {
            session()->flash('error', 'Stok buku "' . $item->book->title . '" tidak mencukupi.');
            $this->dispatch('swal:success', [
                        'icon' => 'success',
                        'title' => 'Error!',
                        'text' => 'Stok Buku tidak mencukupi',
                    ]);
            return;
        }
    }
    
    $this->showRentalModal = true;
}
        
    public function checkout()
    {
        
        if (empty($this->selectedItems)) {
            $this->dispatch('swal:success', [
                        'icon' => 'success',
                        'title' => 'Error!',
                        'text' => 'Pilih minimal satu item untuk checkout.',
                    ]);
            return;
        }

        $items = Cart::whereIn('id', $this->selectedItems)
            ->where('user_id', Auth::id())
            ->with('book')
            ->get();

        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                if ($item->quantity > $item->book->stock) {
                    session()->flash('error', 'Stok buku "' . $item->book->title . '" tidak mencukupi.');
                    $this->dispatch('swal:success', [
                        'icon' => 'success',
                        'title' => 'Error!',
                        'text' => 'Stok Buku tidak mencukupi',
                    ]);
                    DB::rollBack();
                    return;
                }

                $rentalDays = Carbon::parse($item->rental_date)->diffInDays($item->return_date) ;
                $totalPrice = $rentalDays * $item->book->final_price * $item->quantity;

                $rental = Rental::create([
                    'user_id' => $item->user_id,
                    'rental_date' => $item->rental_date,
                    'return_date' => $item->return_date,
                    'total_price' => $totalPrice,
                    'status' => 'rented',
                ]);

                RentalItem::create([
                    'rental_id' => $rental->id,
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'sub_total' => $totalPrice,
                ]);

                Payment::create([
                    'rental_id' => $rental->id,
                    'payment_date' => now(),
                    'amount' => $totalPrice,
                    'method' => 'transfer',
                    'status' => 'paid',
                ]);

                $item->book->decrement('stock', $item->quantity);
            }

            // Hapus hanya item yang di-checkout
            Cart::whereIn('id', $this->selectedItems)->delete();
            
            $this->showRentalModal = false;

            DB::commit();

            $this->selectedItems = [];

            $this->dispatch('swal:success', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Buku berhasil disewa!',

        ]); // 👈 Kirim event ke frontend
            session()->flash('message', 'Checkout berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }

    public function getTotalSelectedPriceProperty()
    {
        $items = Cart::whereIn('id', $this->selectedItems)->with('book')->get();
        $total = 0;

        foreach ($items as $item) {
            $days = Carbon::parse($item->rental_date)->diffInDays($item->return_date) + 1;
            $total += $item->book->price * $item->quantity * $days;
        }

        return $total;
    }


public function removeFromCart($id)
{
    try {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        // Perbarui data tanpa query ulang
        $this->cartItems = $this->cartItems->reject(fn ($item) => $item->id == $id);
        $this->selectedItems = array_diff($this->selectedItems, [$id]);

        $this->dispatch('cart-updated'); // Event untuk notifikasi (opsional)
        session()->flash('message', 'Item berhasil dihapus.');
    } catch (\Exception $e) {
        session()->flash('error', 'Gagal menghapus item: ' . $e->getMessage());
    }
}
public function render()
{
    // Gunakan $this->cartItems yang sudah di-mount
    return view('livewire.customer.cart-component', [
        'cartItems' => $this->cartItems,
    ])->layout('layouts.app');
}
}