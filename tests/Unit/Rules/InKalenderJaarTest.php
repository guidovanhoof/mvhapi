<?php

namespace Tests\Unit\Rules;

use App\Rules\DatumInKalenderJaar;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function App\Helpers\verwijderAccenten;

class InKalenderJaarTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function validationFails()
    {
        $kalender = bewaarKalender(["jaar" => 2020]);
        $validationRule = new DatumInKalenderJaar($kalender->id);

        $this->assertFalse($validationRule->passes("datum", "2019-01-01"));
        $this->assertEquals($validationRule->message(), "Datum niet in kalenderjaar!");
    }

    /** @test  */
    public function validationSucceeds()
    {
        $kalender = bewaarKalender(["jaar" => 2020]);
        $validationRule = new DatumInKalenderJaar($kalender->id);

        $this->assertTrue($validationRule->passes("datum", "2020-04-28"));
    }
}
