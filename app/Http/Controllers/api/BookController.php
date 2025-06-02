<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\BookGenreCustom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $book = Book::all();
        return new BookResource(true,'List Book',$book);
    }


public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title'        => 'required|string',
        'name'         => 'required|string',
        'description'  => 'required|string',
        'price'        => 'required|numeric',
        'stock'        => 'required|numeric',
        'category_id'  => 'required|exists:categories,id',
        'genre_ids'    => 'required|array',
        'genre_ids.*'  => 'exists:genres,id',
        'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Simpan gambar jika dikirim
    $imageName = null;
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = $image->hashName();
        $image->storeAs('books', $imageName);
    }

    // Simpan buku
    $book = Book::create([
        'title'        => $request->title,
        'name'         => $request->name,
        'description'  => $request->description,
        'price'        => $request->price,
        'stock'        => $request->stock,
        'category_id'  => $request->category_id,
        'image'        => $imageName,
    ]);

    $genre_ids = array_map('strval', $request->genre_ids);

    BookGenreCustom::create([
        'book_id'   => $book->id,
        'user_id'   => auth()->id() ?? 1,
        'genre_ids' => $genre_ids, // tanpa json_encode!
    ]);


    return new BookResource(true, 'Data Buku Berhasil Ditambahkan!', $book);
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Book::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->validate([
            'title' => 'required',
            'content' => 'required',
        ]));

        return $book;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id)->delete();
        return new BookResource(true, 'Data Buku Berhasil Dihapus!', $book);
    }
}
