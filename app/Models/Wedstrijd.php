<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use function App\Helpers\verwijderAccenten;

class Wedstrijd extends Model
{
    use HasFactory;

    protected $table = "wedstrijden";
    protected $fillable = [
        'kalender_id', 'datum', 'omschrijving', 'sponsor', 'aanvang', 'wedstrijdtype_id', 'opmerkingen', 'nummer'
    ];

    /**
     * @param $value
     */
    public function setOmschrijvingAttribute($value)
    {
        $this->attributes["omschrijving"] = strtoupper(verwijderAccenten($value));
    }

    /**
     * @param $value
     */
    public function setSponsorAttribute($value)
    {
        $this->attributes["sponsor"] = strtoupper(verwijderAccenten($value));
    }

    /**
     * @return BelongsTo
     */
    public function kalender(): BelongsTo
    {
        return $this->belongsTo(Kalender::class);
    }

    /**
     * @return HasMany
     */
    public function reeksen(): HasMany
    {
        return $this->hasMany(Reeks::class);
    }

    /**
     * @return HasMany
     */
    public function deelnemers(): HasMany
    {
        return $this->hasMany(Wedstrijddeelnemer::class);
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'datum';
    }
}
