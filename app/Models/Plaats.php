<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plaats extends Model
{
    use HasFactory;

    protected $table = "plaatsen";
    protected $fillable = [
        "reeks_id", "nummer", "opmerkingen"
    ];

    /**
     * Ophalen alle gewichten van een plaats.
     *
     * @return HasMany
     */
    public function gewichten(): HasMany
    {
        return $this->hasMany(Gewicht::class);
    }

    /**
     * Ophalen alle deelnemers van een plaats.
     *
     * @return HasMany
     */
    public function deelnemers(): HasMany
    {
        return $this->hasMany(Plaatsdeelnemer::class);
    }
}
