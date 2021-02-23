<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plaatsdeelnemer extends Model
{
    protected $table = 'plaatsdeelnemers';
    protected $fillable = [
        'plaats_id', 'wedstrijddeelnemer_id', 'is_weger',
    ];

    use HasFactory;
}
