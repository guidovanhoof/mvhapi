<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reeks extends Model
{
    use HasFactory;

    protected $table = "reeksen";
    protected $fillable = [
        'wedstrijd_id', 'nummer', 'aanvang', 'duur', 'gewicht_zak', 'opmerkingen',
    ];

    public function plaatsen()
    {
        return $this->hasMany(Plaats::class);
    }
}
