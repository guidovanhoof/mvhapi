<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WedstrijddeelnemerJeugdcategorie extends Model
{
    use HasFactory;

    protected $table = 'wdeelnemers_jcategorieen';
    protected $fillable = [
        'wedstrijddeelnemer_id',
        'jeugdcategorie_id',
    ];

    /**
     * @return BelongsTo
     */
    public function jeugdcategorie(): BelongsTo
    {
        return $this->belongsTo(Jeugdcategorie::class);
    }
}
