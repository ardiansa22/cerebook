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
    public $paymentMethod; // Tambahkan ini
    public $selectedItems = []; // untuk menyimpan ID cart yang dipilih user
    public $quantities = [];
    public $cartItems = [];


   public function mount()
    {
        $this->cartItems = Cart::with('book')->where('user_id', Auth::id())->get();
        foreach ($this->cartItems as $item) {
            $this->quantities[$item->id] = $item->quantity;
        }
    }


    public function updateQuantity($itemId, $newQuantity)
    {
        $item = Cart::find($itemId);
        if ($item && $newQuantity > 0) {
            $item->quantity = $newQuantity;
            $item->save();
            $this->mount(); // agar keranjang diperbarui di tampilan
            session()->flash('message', 'Jumlah berhasil diperbarui.');
        }
    }


    public function addToCart()
    {
        $this->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'rental_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:rental_date',
        ]);

        Cart::create([
            'user_id' => Auth::id(),
            'book_id' => $this->book_id,
            'quantity' => $this->quantity,
            'rental_date' => $this->rental_date,
            'return_date' => $this->return_date,
        ]);

        $this->reset(['book_id', 'quantity', 'rental_date', 'return_date']);
        session()->flash('message', 'Buku ditambahkan ke keranjang.');
    }
        
    public function checkout()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Pilih minimal satu item untuk checkout.');
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
                    DB::rollBack();
                    return;
                }

                $rentalDays = Carbon::parse($item->rental_date)->diffInDays($item->return_date) + 1;
                $totalPrice = $rentalDays * $item->book->price * $item->quantity;

                $rental = Rental::create([
                    'user_id' => $item->user_id,
                    'book_id' => $item->book_id,
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
                    'method' => $this->paymentMethod,
                    'status' => 'paid',
                ]);

                $item->book->decrement('stock', $item->quantity);
            }

            // Hapus hanya item yang di-checkout
            Cart::whereIn('id', $this->selectedItems)->delete();
            

            DB::commit();

            $this->selectedItems = [];
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
        Cart::find($id)?->delete();
    }
    public function render()
    {
        return view('livewire.customer.cart-component',[
            'cartItems' => Cart::with('book')->where('user_id', Auth::id())->get(),
        ])->layout('layouts.app');
    }
}
