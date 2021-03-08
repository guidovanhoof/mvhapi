<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     * @return HasOne
     */
    public function jeugdcategorie(): HasOne
    {
        return $this->hasOne(WedstrijddeelnemerJeugdcategorie::class);
    }
}
