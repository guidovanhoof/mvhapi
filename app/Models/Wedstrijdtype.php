<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function App\Helpers\verwijderAccenten;

class Wedstrijdtype extends Model
{
    use HasFactory;


    protected $table = "wedstrijdtypes";
    protected $fillable = ['omschrijving'];

    /**
     * @param $value
     */
    public function setOmschrijvingAttribute($value)
    {
        $this->attributes["omschrijving"] = strtoupper(verwijderAccenten($value));
    }
}
