<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wedstrijd extends Model
{
    use HasFactory;

    protected $table = "wedstrijden";
    protected $fillable = [
        'kalender_id', 'datum', 'omschrijving', 'sponsor', 'aanvang', 'wedstrijdtype_id', 'opmerkingen', 'nummer'
    ];

    public function kalender()
    {
        return $this->belongsTo(Kalender::class);
    }

    public function getRouteKeyName()
    {
        return 'datum';
    }
}
