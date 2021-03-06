<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kalender extends Model
{
    use HasFactory;

    protected $table = "kalenders";
    const PREFIX = 'KALENDER ';
    const URL_PREFIX = 'api/kalenders/';

    protected $fillable = ['jaar', 'opmerkingen'];

    public function omschrijving(): string
    {
        return self::PREFIX . $this->jaar;
    }

    public function getRouteKeyName(): string
    {
        return 'jaar';
    }

    public function wedstrijden()
    {
        return $this
            ->hasMany(Wedstrijd::class)
            ->orderBy("datum", "desc")
        ;
    }
}
