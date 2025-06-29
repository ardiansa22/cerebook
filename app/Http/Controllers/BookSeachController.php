<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookSeachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function results(Request $request)
{
    $request->validate([
        'query' => 'nullable|string|min:2', // Memastikan query adalah string dan minimal 2 karakter
    ]);

    $query = $request->input('query');
    $books = collect(); // Inisialisasi koleksi kosong

    if ($query && strlen($query) >= 2) { // Hanya jalankan pencarian jika query valid
        $books = Book::where('title', 'like', '%' . $query . '%')
                     ->limit(10) // Batasi jumlah hasil untuk performa
                     ->get();
    }

    return view('livewire.customer.search-result', compact('books', 'query'))->layout('layouts.conapp');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
