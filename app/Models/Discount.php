<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'book_id',
        'percentage',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    
}
