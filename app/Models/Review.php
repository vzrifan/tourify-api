<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public function tempatWisata()
    {
        return $this->belongsTo(TempatWisata::class);
    }
}
