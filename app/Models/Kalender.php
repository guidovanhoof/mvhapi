<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kalender extends Model
{
    use HasFactory;
    protected $table="kalenders";
    const PREFIX = 'Kalender ';
    const URL_PREFIX = 'api/kalenders/';

    protected $fillable = ['jaar', 'opmerkingen'];

    public function omschrijving() {
        return self::PREFIX . $this->jaar;
    }

    public function link() {
        return self::URL_PREFIX . $this->jaar;
    }

    public function getRouteKeyName()
    {
        return 'jaar';
    }
}
