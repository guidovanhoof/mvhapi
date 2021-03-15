<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wedstrijddeelnemer extends Model
{
    use HasFactory;

    protected $table = 'wedstrijddeelnemers';
    protected $fillable = [
        'wedstrijd_id', 'deelnemer_id', 'is_gediskwalificeerd', 'opmerkingen',
    ];

    /**
     * Wedstrijddeelnemer kan 1 jeugdcategorie hebben.
     *
     * @return Jeugdcategorie|null
     */
    public function jeugdcategorie(): ?Jeugdcategorie
    {
        $wedstrijddeelnemerJeugdcategorie = WedstrijddeelnemerJeugdcategorie::where(
            'wedstrijddeelnemer_id', $this->id
        )
            ->first();
        return $wedstrijddeelnemerJeugdcategorie
            ? $wedstrijddeelnemerJeugdcategorie->jeugdcategorie
            : null
        ;
    }

    /**
     * Wedstrijddeelnemer heeft 1 deelnemer.
     *
     * @return BelongsTo
     */
    public function deelnemer(): BelongsTo
    {
        return $this->belongsTo(Deelnemer::class);
    }

    /**
     * Wedstrijddeelnemer heeft 1 getrokken maat.
     *
     */
    public function getrokkenMaat()
    {
        $getrokkenmaat = GetrokkenMaat::where(
            'wedstrijddeelnemer_id', $this->id
        )
            ->first();
        $wedstrijddeelnemer = Wedstrijddeelnemer::where(
            'id', $getrokkenmaat->getrokken_maat_id
        )
            ->first();
        return $wedstrijddeelnemer
            ? $wedstrijddeelnemer->deelnemer
            : null
        ;
    }
}
