<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    protected $fillable = ['name', 'image', 'title', 'description', 'price', 'stock', 'product_category_id'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
