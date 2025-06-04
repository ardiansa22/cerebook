<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fines extends Model
{
    /** @use HasFactory<\Database\Factories\FinesFactory> */
    protected $fillable = [
    'rental_id',
    'fine_amount',
    'days_late', // Pastikan ini ada
    'paid'
];
    use HasFactory;
    public function rental() {
        return $this->belongsTo(Rental::class);
    }

}
