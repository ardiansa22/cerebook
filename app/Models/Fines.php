<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fines extends Model
{
    /** @use HasFactory<\Database\Factories\FinesFactory> */
    use HasFactory;
    public function rental() {
        return $this->belongsTo(Rental::class);
    }

}
