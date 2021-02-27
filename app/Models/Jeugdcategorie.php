<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function App\Helpers\verwijderAccenten;

class Jeugdcategorie extends Model
{
    use HasFactory;

    protected $table = 'jeugdcategorieen';
    protected $fillable = [
        'omschrijving',
    ];

    /**
     * @param $omschrijving
     */
    public function setOmschrijvingAttribute($omschrijving)
    {
        $this->attributes["omschrijving"] = strtoupper(verwijderAccenten($omschrijving));
    }
}
