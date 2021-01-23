<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wedstrijd extends Model
{
    use HasFactory;

    protected $table = "wedstrijden";
    protected $fillable = [
        'kalender_id', 'datum', 'omschrijving', 'sponsor', 'aanvang', 'wedstrijdtype_id', 'opmerkingen', 'nummer'
    ];

    /**
     * @return BelongsTo
     */
    public function kalender(): BelongsTo
    {
        return $this->belongsTo(Kalender::class);
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'datum';
    }
}
