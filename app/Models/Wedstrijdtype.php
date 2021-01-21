<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $this->attributes["omschrijving"] = preg_replace('/[^A-Z\s]/', '', strtoupper($value));
    }
}
