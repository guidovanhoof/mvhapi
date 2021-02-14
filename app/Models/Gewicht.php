<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gewicht extends Model
{
    use HasFactory;

    protected $table = "gewichten";
    protected $fillable = [
        "plaats_id", "gewicht", "is_geldig",
    ];
}
