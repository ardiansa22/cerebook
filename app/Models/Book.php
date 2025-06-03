<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['name', 'image', 'title', 'description', 'price', 'stock', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function genres()
    {
        return $this->belongsToMany (Genre::class);
    }
    public function rentalItems() {
    return $this->hasMany(RentalItem::class);
    }
    public function rentals() {
    return $this->hasMany(Rental::class);
    }

    public function reviews() {
        return $this->hasMany(BookReview::class);
    }

}
