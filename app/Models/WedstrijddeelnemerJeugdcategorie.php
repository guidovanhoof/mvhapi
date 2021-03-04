<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WedstrijddeelnemerJeugdcategorie extends Model
{
    use HasFactory;

    protected $table = 'wdeelnemers_jcategorieen';
    protected $fillable = [
        'wedstrijddeelnemer_id',
        'jeugdcategorie_id',
    ];
}
