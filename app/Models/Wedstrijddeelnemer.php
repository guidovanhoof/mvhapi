<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wedstrijddeelnemer extends Model
{
    use HasFactory;

    protected $table = 'wedstrijddeelnemers';
    protected $fillable = [
        'wedstrijd_id', 'deelnemer_id', 'is_gediskwalificeerd', 'opmerkingen',
    ];
}
