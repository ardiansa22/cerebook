<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    /** @use HasFactory<\Database\Factories\RentalItemFactory> */
    protected $fillable = ['rental_id', 'book_id','quantity','sub_total',"rental_date",'return_date'];
    use HasFactory;
    public function rental() {
    return $this->belongsTo(Rental::class);
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }
     public function payment()
    {
        return $this->hasOneThrough(
            Payment::class,
            Rental::class,
            'id', // Foreign key on rentals table
            'rental_id', // Foreign key on payments table
            'rental_id', // Local key on rental_items table
            'id' // Local key on rentals table
        );
    }

}
