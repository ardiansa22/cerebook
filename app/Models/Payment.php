<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    protected $fillable = ['rental_id', 'payment_date', 'amount','method','status'];
    use HasFactory;
    public function rental() {
    return $this->belongsTo(Rental::class);
    }

}
