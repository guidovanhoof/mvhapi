<?php

namespace Tests\Unit\Kalender;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KalenderTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function heeftEenJaar()
    {
        $jaar = date('Y');
        $kalender = bewaarKalender(['jaar' => $jaar]);

        $this->assertEquals($jaar, $kalender->jaar);
    }

    /** @test  */
    public function heeftOpmerkingen()
    {
        $opmerkingen = 'OPMERKINGEN';
        $kalender = bewaarKalender(['opmerkingen' => $opmerkingen]);

        $this->assertEquals($opmerkingen, $kalender->opmerkingen);
    }

    /** @test  */
    public function heeftEenOmschrijving()
    {
        $jaar = date('Y');
        $omschrijving = 'KALENDER ';
        $kalender = maakKalender(['jaar' => $jaar]);

        $this->assertEquals($omschrijving . $jaar, $kalender->omschrijving());
    }

    /** @test  */
    public function heeftJaarAlsRouteKeyName()
    {
        $kalender = maakKalender();

        $this->assertEquals('jaar', $kalender->getRouteKeyName());
    }

    /** @test  */
    public function opmerkingenIsOptioneel()
    {
        $kalender = bewaarKalender(['opmerkingen' => null]);

        $this->assertEquals(null, $kalender->opmerkingen);
    }

    /** @test  */
    public function jaarIsUniek()
    {
        $this->expectException(QueryException::class);

        $jaar = date('Y');

        bewaarKalender(['jaar' => $jaar]);
        bewaarKalender(['jaar' => $jaar]);
    }
}
