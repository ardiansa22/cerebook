<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userbook extends Model
{
    protected $fillable = ['transaction_id', 'book_id'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
