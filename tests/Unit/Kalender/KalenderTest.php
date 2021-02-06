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

    /** @test  */
    public function heeftWedstrijden()
    {
        $kalender = bewaarKalender();
        $expectedWedstrijd = bewaarWedstrijd(["kalender_id" => $kalender->id]);

        $actualWedstrijden = $kalender->wedstrijden;

        $this->assertCount(1, $actualWedstrijden);
        $this->assertEquals($expectedWedstrijd->kalender_id, $actualWedstrijden[0]->kalender_id);
        $this->assertEquals($expectedWedstrijd->jaar, $actualWedstrijden[0]->jaar);
        $this->assertEquals($expectedWedstrijd->opmerkingen, $actualWedstrijden[0]->opmerkingen);
    }

    /** @test */
    public function wedstrijdenGesorteerdOpDatumAflopend()
    {
        $kalender = bewaarKalender();
        $eersteWedstrijd = bewaarWedstrijd(
            [
                "kalender_id" => $kalender->id,
                "datum" => $kalender->jaar . "-04-28",
            ]
        );
        $tweedeWedstrijd = bewaarWedstrijd(
            [
                "kalender_id" => $kalender->id,
                "datum" => $kalender->jaar . "-05-28",
                "wedstrijdtype_id" => $eersteWedstrijd->wedstrijdtype_id,
            ]
        );

        $actualWedstrijden = $kalender->wedstrijden;

        $this->assertCount(2, $actualWedstrijden);
        assertWedstrijdEquals($this, $actualWedstrijden[0], $tweedeWedstrijd);
        assertWedstrijdEquals($this, $actualWedstrijden[1], $eersteWedstrijd);
    }
}
