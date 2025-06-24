<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['name', 'image', 'title', 'description', 'price', 'stock', 'category_id','rent_price','fines_price'];

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


    public function reviews() {
        return $this->hasMany(BookReview::class);
    }
    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    // Jika ingin ambil diskon aktif
    public function activeDiscount()
    {
        return $this->hasOne(Discount::class)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }
        public function getFinalPriceAttribute()
{
    $activeDiscount = $this->discounts()
        ->where('is_active', true)
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->first();

    if ($activeDiscount) {
        return $this->rent_price - ($this->rent_price * $activeDiscount->percentage / 100);
    }

    return $this->rent_price;
}

public function getActiveDiscountAttribute()
{
    return $this->discounts()
        ->where('is_active', true)
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->first();
}


}
