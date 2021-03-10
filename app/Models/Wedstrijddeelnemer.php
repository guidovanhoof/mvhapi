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
}
