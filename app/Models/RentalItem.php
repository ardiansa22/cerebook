<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    /** @use HasFactory<\Database\Factories\RentalItemFactory> */
    protected $fillable = ['rental_id', 'book_id','quantity','sub_total'];
    use HasFactory;
    public function rental() {
    return $this->belongsTo(Rental::class);
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }

}
