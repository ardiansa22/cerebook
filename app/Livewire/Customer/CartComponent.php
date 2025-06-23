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
    public $paymentMethod = 'transfer'; // Set default value
    public $selectedItems = [];
    public $quantities = [];
    public $cartItems = [];
    public $showRentalModal = false;
    public $totalPrice = 0; // Add total price property

    public function mount()
    {
        $this->cartItems = Cart::with(['book' => function ($query) {
            $query->select('id', 'title', 'image');
        }])->where('user_id', Auth::id())->get();

        $this->quantities = $this->cartItems->pluck('quantity', 'id')->toArray();
        $this->calculateTotalPrice(); // Calculate initial total price
    }

    public function calculateTotalPrice()
    {
        $this->totalPrice = 0;
        $items = Cart::whereIn('id', $this->selectedItems)
            ->where('user_id', Auth::id())
            ->with('book')
            ->get();

        foreach ($items as $item) {
            $days = Carbon::parse($item->rental_date)->diffInDays($item->return_date) + 1;
            $this->totalPrice += $item->book->final_price * $item->quantity * $days;
        }
    }

    public function updatedSelectedItems()
    {
        $this->calculateTotalPrice();
    }

    public function updateQuantity($itemId, $newQuantity)
    {
        $item = Cart::find($itemId);
        if ($item && $newQuantity > 0) {
            $item->quantity = $newQuantity;
            $item->save();
            $this->mount();
            $this->calculateTotalPrice();
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

    public function OpenRentalModal()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Pilih minimal satu item untuk checkout.');
            return;
        }
        
        $items = Cart::whereIn('id', $this->selectedItems)
            ->where('user_id', Auth::id())
            ->with('book')
            ->get();
            
        foreach ($items as $item) {
            if ($item->quantity > $item->book->stock) {
                session()->flash('error', 'Stok buku "' . $item->book->title . '" tidak mencukupi.');
                return;
            }
        }
        
        $this->showRentalModal = true;
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

     
            $rentals = [];
            $totalAmount = 0;

            foreach ($items as $item) {
                if ($item->quantity > $item->book->stock) {
                    session()->flash('error', 'Stok buku "' . $item->book->title . '" tidak mencukupi.');
                    DB::rollBack();
                    return;
                }

                $rentalDays = Carbon::parse($item->rental_date)->diffInDays($item->return_date);
                $totalPrice = $rentalDays * $item->book->final_price * $item->quantity;
                $totalAmount += $totalPrice;

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

                $rentals[] = $rental;
            }

            // Create single payment for all rentals
            $payment = Payment::create([
                'rental_id' => $rental->id,
                'payment_date' => now(),
                'amount' => $totalAmount,
                'method' => $this->paymentMethod,
                'status' => 'pending',
            ]);



            // Midtrans integration
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'CART-' . $payment->id . '-' . time(),
                    'gross_amount' => $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()?->name ?? 'Guest',
                    'email' => Auth::user()?->email ?? 'guest@example.com',
                    'phone' => Auth::user()?->phone ?? '0811111111',
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Update payment with snap token
            $payment->update([
                'snap_token' => $snapToken
            ]);

            // Delete cart items only after successful payment creation
            Cart::whereIn('id', $this->selectedItems)->delete();
            
            $this->showRentalModal = false;
            $this->selectedItems = [];

            DB::commit();

            $this->dispatch('midtrans:pay', [
                'snapToken' => $snapToken
            ]);

    }

    public function getTotalSelectedPriceProperty()
    {
        return $this->totalPrice;
    }

    public function removeFromCart($id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            $cartItem->delete();

            $this->cartItems = $this->cartItems->reject(fn ($item) => $item->id == $id);
            $this->selectedItems = array_diff($this->selectedItems, [$id]);
            $this->calculateTotalPrice();

            $this->dispatch('cart-updated');
            session()->flash('message', 'Item berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.customer.cart-component', [
            'cartItems' => $this->cartItems,
        ])->layout('layouts.app');
    }
}