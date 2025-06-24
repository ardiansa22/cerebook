<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    /** @use HasFactory<\Database\Factories\RentalFactory> */
    protected $fillable = ['user_id', 'status', 'book_id','rental_date','return_date','total_price','actual_return_date','return_evidence'];
    protected $casts = [
    'rental_date' => 'date',
    'return_date' => 'date',
    'actual_return_date' => 'date',
    'actual_return_date' => 'date',
];


    use HasFactory;
    public function user() {
    return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(RentalItem::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }

    public function fine() {
        return $this->hasOne(Fines::class);
    }
  public function book()
{
    return $this->belongsTo(Book::class);
}


}
