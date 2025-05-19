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

}
