<?php

namespace Tests\Unit\Wedstrijd;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WedstrijdTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function heeftEenDatum()
    {
        $datum = date('Y-m-d');
        $wedstrijd = bewaarWedstrijd(['datum' => $datum]);

        $this->assertEquals($datum, $wedstrijd->datum);
    }

    /** @test  */
    public function datumIsUniek()
    {
        $this->expectException(QueryException::class);

        $datum = date('Y-m-d');

        bewaarWedstrijd(['datum' => $datum]);
        bewaarWedstrijd(['datum' => $datum]);
    }

    /** @test  */
    public function heeftEenOmschrijving()
    {
        $omschrijving = 'wedstrijdomschrijving';
        $wedstrijd = bewaarWedstrijd(['omschrijving' => $omschrijving]);

        $this->assertEquals(strtoupper($omschrijving), $wedstrijd->omschrijving);
    }

    /** @test  */
    public function omschrijvingIsInHoofdletters()
    {
        $omschrijvingKleineLetters = "wedstrijd";
        $omschrijvingHoofdletters = "WEDSTRIJD";

        $wedstrijd = bewaarWedstrijd(['omschrijving' => $omschrijvingKleineLetters]);

        $this->assertEquals($omschrijvingHoofdletters, $wedstrijd->omschrijving);
    }

    /** @test  */
    public function omschrijvingHeeftGeenAccenten()
    {
        $omschrijving = "áéýúíóàèùìòâêûîô^´`¨äëÿüïö~ãõñç";
        $letters = "AEYUIOAEUIOAEUIOAEYUIOAONC";

        $wedstrijd = bewaarWedstrijd(['omschrijving' => $omschrijving]);

        $this->assertEquals($letters, $wedstrijd->omschrijving);
    }

    /** @test  */
    public function heeftOpmerkingen()
    {
        $opmerkingen = 'OPMERKINGEN';
        $wedstrijd = bewaarWedstrijd(['opmerkingen' => $opmerkingen]);

        $this->assertEquals($opmerkingen, $wedstrijd->opmerkingen);
    }

    /** @test  */
    public function opmerkingenIsOptioneel()
    {
        $wedstrijd = bewaarWedstrijd(['opmerkingen' => null]);

        $this->assertEquals(null, $wedstrijd->opmerkingen);
    }

    /** @test  */
    public function heeftEenSponsor()
    {
        $sponsor = 'SPONSOR';
        $wedstrijd = bewaarWedstrijd(['sponsor' => $sponsor]);

        $this->assertEquals($sponsor, $wedstrijd->sponsor);
    }

    /** @test  */
    public function sponsorIsInHoofdletters()
    {
        $sponsorKleineLetters = "sponsor";
        $sponsorHoofdletters = "SPONSOR";

        $wedstrijd = bewaarWedstrijd(['sponsor' => $sponsorKleineLetters]);

        $this->assertEquals($sponsorHoofdletters, $wedstrijd->sponsor);
    }

    /** @test  */
    public function sponsorHeeftGeenAccenten()
    {
        $sponsor = "áéýúíóàèùìòâêûîô^´`¨äëÿüïö~ãõñç";
        $letters = "AEYUIOAEUIOAEUIOAEYUIOAONC";

        $wedstrijd = bewaarWedstrijd(['sponsor' => $sponsor]);

        $this->assertEquals($letters, $wedstrijd->sponsor);
    }

    /** @test  */
    public function sponsorIsOptioneel()
    {
        $wedstrijd = bewaarWedstrijd(['sponsor' => null]);

        $this->assertEquals(null, $wedstrijd->sponsor);
    }

    /** @test  */
    public function heeftEenKalenderId()
    {
        $kalender = bewaarKalender();
        $wedstrijd = bewaarWedstrijd(['kalender_id' => $kalender->id]);

        $this->assertEquals($kalender->id, $wedstrijd->kalender_id);
    }

    /** @test  */
    public function heeftEenKalender()
    {
        $kalender = bewaarKalender();
        $wedstrijd = bewaarWedstrijd(['kalender_id' => $kalender->id]);

        $this->assertEquals($kalender->fresh(), $wedstrijd->kalender);
    }

    /** @test  */
    public function heeftEenAanvang()
    {
        $aanvang = date('H:i:s');
        $wedstrijd = bewaarWedstrijd(['aanvang' => $aanvang]);

        $this->assertEquals($aanvang, $wedstrijd->fresh()->aanvang);
    }

    /** @test  */
    public function heeftEenWedstrijtypeId()
    {
        $wedstrijdtype = bewaarWedstrijdtype();
        $wedstrijd = bewaarWedstrijd(['wedstrijdtype_id' => $wedstrijdtype->id]);

        $this->assertEquals($wedstrijdtype->id, $wedstrijd->wedstrijdtype_id);
    }

    /** @test */
    public function heeftEenNummer()
    {
        $nummer = 1;
        $wedstrijd = bewaarWedstrijd(['nummer' => $nummer]);

        $this->assertEquals($nummer, $wedstrijd->nummer);
    }

    /** @test */
    public function nummerIsOptioneel()
    {
        $nummer = null;
        $wedstrijd = bewaarWedstrijd(['nummer' => $nummer]);

        $this->assertEquals($nummer, $wedstrijd->nummer);
    }

    /** @test  */
    public function heeftEenForeignKeyNaarKalender()
    {
        $this->expectException(QueryException::class);

        $kalender = bewaarKalender();
        bewaarWedstrijd(['kalender_id' => $kalender->id]);

        $kalender->delete();
    }

    /** @test  */
    public function heeftEenForeignKeyNaarWedstrijdtype()
    {
        $this->expectException(QueryException::class);

        $wedstrijdtype = bewaarWedstrijdtype();
        bewaarWedstrijd(['wedstrijdtype_id' => $wedstrijdtype->id]);

        $wedstrijdtype->delete();
    }

    /** @test  */
    public function heeftDatumAlsRouteKeyName()
    {
        $wedstrijd = maakWedstrijd();

        $this->assertEquals('datum', $wedstrijd->getRouteKeyName());
    }

    /** @test  */
    public function heeftReeksen()
    {
        $wedstrijd = bewaarWedstrijd();
        $expectedReeks = bewaarReeks(["wedstrijd_id" => $wedstrijd->id]);

        $actualReeksen = $wedstrijd->reeksen;

        $this->assertCount(1, $actualReeksen);
        assertReeksEquals($this, $actualReeksen[0], $expectedReeks);
    }

    /** @test  */
    public function heeftDeelnemers()
    {
        $wedstrijd = bewaarWedstrijd();
        $expectedDeelnemer = bewaarWedstrijddeelnemer(["wedstrijd_id" => $wedstrijd->id]);

        $actualDeelnemers = $wedstrijd->deelnemers;

        $this->assertCount(1, $actualDeelnemers);
        assertWedstrijddeelnemerEquals($this, $actualDeelnemers[0], $expectedDeelnemer);
    }
}
