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
            $this->dispatch('swal:success', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Jumlah Berhasil diperbaharui',
        ]);
        }
    }

    public function OpenRentalModal()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('swal:success', [
            'icon' => 'success',
            'title' => 'Error!',
            'text' => 'Pilih minimal satu item untuk dipilih',
        ]);
            return;
        }
        
        $items = Cart::whereIn('id', $this->selectedItems)
            ->where('user_id', Auth::id())
            ->with('book')
            ->get();
            
        foreach ($items as $item) {
            if ($item->quantity > $item->book->stock) {
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
        session()->flash('error', 'Pilih minimal satu item untuk checkout.');
        return;
    }

    $items = Cart::whereIn('id', $this->selectedItems)
        ->where('user_id', Auth::id())
        ->with('book')
        ->get();

    DB::beginTransaction();

    try {
        $totalAmount = 0;

        // Cek stok dan total
        foreach ($items as $item) {
            if ($item->quantity > $item->book->stock) {
                DB::rollBack();
                session()->flash('error', 'Stok buku "' . $item->book->title . '" tidak mencukupi.');
                return;
            }

            $rentalDays = max(Carbon::parse($item->rental_date)->diffInDays(Carbon::parse($item->return_date)), 1);
            $totalAmount += $rentalDays * $item->book->final_price * $item->quantity;
        }

        // Buat satu Rental utama
        $firstItem = $items->first();

        $rental = Rental::create([
            'user_id' => Auth::id(),
            'rental_date' => $firstItem->rental_date,
            'return_date' => $firstItem->return_date,
            'total_price' => $totalAmount,
            'status' => 'rented',
        ]);

        // Tambahkan semua item
        foreach ($items as $item) {
            $book = $item->book;

            $rentalDays = max(Carbon::parse($item->rental_date)->diffInDays(Carbon::parse($item->return_date)), 1);
            $subTotal = $book->final_price * $item->quantity * $rentalDays;

            RentalItem::create([
                'rental_id' => $rental->id,
                'book_id' => $book->id,
                'quantity' => $item->quantity,
                'sub_total' => $subTotal,
            ]);

            // Kurangi stok
            $book->decrement('stock', $item->quantity);
        }

        // Buat payment
        $payment = Payment::create([
            'rental_id' => $rental->id,
            'payment_date' => now(),
            'amount' => $totalAmount,
            'method' => $this->paymentMethod,
            'status' => 'pending',
        ]);

        // Midtrans config
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'test1-' . $rental->id,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name ?? 'Guest',
                'email' => Auth::user()->email ?? 'guest@example.com',
                'phone' => Auth::user()->phone ?? '0811111111',
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
Payment::where('rental_id', $rental->id)->update([
                'snap_token' => $snapToken // tambahkan ini, pastikan kolomnya ada
            ]);

        // Bersihkan keranjang
        Cart::whereIn('id', $this->selectedItems)->delete();

        DB::commit();

        $this->showRentalModal = false;
        $this->selectedItems = [];

        $this->dispatch('midtrans:pay', ['snapToken' => $snapToken]);
        $this->dispatch('swal:success', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Buku berhasil disewa',
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Checkout gagal: ' . $e->getMessage());
    }
}


    public function getTotalSelectedPriceProperty()
    {
        return $this->totalPrice;
    }

    public function removeFromCart($id)
    {

            $cartItem = Cart::findOrFail($id);
            $cartItem->delete();

            $this->cartItems = $this->cartItems->reject(fn ($item) => $item->id == $id);
            $this->selectedItems = array_diff($this->selectedItems, [$id]);
            $this->calculateTotalPrice();

            $this->dispatch('cart-updated');        $this->dispatch('swal:success', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Buku berhasil dihapus',
        ]);
    }

    public function render()
    {
        return view('livewire.customer.cart-component', [
            'cartItems' => $this->cartItems,
        ])->layout('layouts.app');
    }
}