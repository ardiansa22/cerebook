<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Illuminate\Support\Facades\Route;

class BreadcrumbComponent extends Component
{
    public $items = [];

    public function mount()
    {
        $route = Route::currentRouteName();

        $this->items[] = ['label' => 'Home', 'route' => route('home')];

        switch ($route) {
            case 'my-books':
                $this->items[] = ['label' => 'My Books'];
                break;

            case 'showbook':
                $this->items[] = ['label' => 'My Books', 'route' => route('my-books')];
                $this->items[] = ['label' => 'Detail'];
                break;

            case 'book.show':
                $this->items[] = ['label' => 'Detail Buku'];
                break;

            case 'keranjang':
                $this->items[] = ['label' => 'Keranjang'];
                break;

            case 'categori.book':
                $kategori = request()->route('categori');
                $this->items[] = ['label' => $kategori];
                break;
        }
    }

    public function render()
    {
        return view('livewire.customer.breadcrumb-component')->layout('layouts.app');
    }
}
