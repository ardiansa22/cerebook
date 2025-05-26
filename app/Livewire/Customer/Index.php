<?php

namespace App\Livewire\Customer;

use App\Models\Book;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;


class Index extends Component
{
    public function render()
    {
        $books = Book::all();
        $products = Product::all();
        $categories = Category::where('is_active', true)->get();
        return view('livewire.customer.index',[
            'books' => $books,
            'categories' => $categories,
            'products' => $products,
        ])->layout('layouts.app');
    }
    
}
