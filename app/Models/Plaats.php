<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plaats extends Model
{
    protected $table = "plaatsen";
    protected $fillable = [
        "reeks_id", "nummer", "opmerkingen"
    ];

    use HasFactory;
}
