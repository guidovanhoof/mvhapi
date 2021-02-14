<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plaats extends Model
{
    use HasFactory;

    protected $table = "plaatsen";
    protected $fillable = [
        "reeks_id", "nummer", "opmerkingen"
    ];

    public function gewichten()
    {
        return $this->hasMany(Gewicht::class);
    }
}
