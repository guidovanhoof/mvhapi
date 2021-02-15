<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function App\Helpers\verwijderAccenten;

class Deelnemer extends Model
{
    use HasFactory;

    protected $table = "deelnemers";
    protected $fillable = [
        'nummer', "naam",
    ];

    /**
     * @param $value
     */
    public function setNaamAttribute($value)
    {
        $this->attributes["naam"] = strtoupper(verwijderAccenten($value));
    }
}
