<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookGenreCustom extends Model
{
    protected $table = 'book_genres_custom';

    protected $fillable = ['book_id', 'genre_ids'];

    protected $casts = [
        'genre_ids' => 'array',
    ];
     public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
