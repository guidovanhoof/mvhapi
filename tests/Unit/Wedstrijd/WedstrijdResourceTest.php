<?php

namespace Tests\Unit\Wedstrijd;

use App\Http\Resources\WedstrijdResource;
use App\Models\Wedstrijd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WedstrijdResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function heeftEenId()
    {
        $kalender = bewaarKalender();
        $wedstrijd = bewaarWedstrijd(['kalender_id' => $kalender->id]);

        $wedstrijdResource = WedstrijdResource::collection(Wedstrijd::first()->get())->resolve();

        $this->assertEquals($wedstrijd->id, $wedstrijdResource[0]["id"]);
    }

    /** @test  */
    public function heeftEenKalenderId()
    {
        $kalender = bewaarKalender();
        bewaarWedstrijd(['kalender_id' => $kalender->id]);

        $wedstrijdResource = WedstrijdResource::collection(Wedstrijd::first()->get())->resolve();

        $this->assertEquals($kalender->id, $wedstrijdResource[0]["kalender_id"]);
    }

    /** @test  */
    public function heeftEenDatum()
    {
        $datum = date('Y-m-d');
        bewaarWedstrijd(['datum' => $datum]);

        $wedstrijdResource = WedstrijdResource::collection(Wedstrijd::first()->get())->resolve();

        $this->assertEquals($datum, $wedstrijdResource[0]["datum"]);
    }

    /** @test  */
    public function heeftEenNummer()
    {
        $nummer = 666;
        bewaarWedstrijd(['nummer' => $nummer]);

        $wedstrijdResource = WedstrijdResource::collection(Wedstrijd::first()->get())->resolve();

        $this->assertEquals($nummer, $wedstrijdResource[0]["nummer"]);
    }

    /** @test  */
    public function heeftEenOmschrijving()
    {
        $omschrijving = 'ledenwedstrijd';
        bewaarWedstrijd(['omschrijving' => $omschrijving]);

        $wedstrijdResource = WedstrijdResource::collection(Wedstrijd::first()->get())->resolve();

        $this->assertEquals(strtoupper($omschrijving), $wedstrijdResource[0]["omschrijving"]);
    }

    /** @test  */
    public function heeftEenSponsor()
    {
        $sponsor = 'SPONSOR';
        bewaarWedstrijd(['sponsor' => $sponsor]);

        $wedstrijdResource = WedstrijdResource::collection(Wedstrijd::first()->get())->resolve();

        $this->assertEquals(strtoupper($sponsor), $wedstrijdResource[0]["sponsor"]);
    }

    /** @test  */
    public function heeftEenAanvang()
    {
        $aanvang = date('H:i:s');
        bewaarWedstrijd(['aanvang' => $aanvang]);

        $wedstrijdResource = WedstrijdResource::collection(Wedstrijd::first()->get())->resolve();

        $this->assertEquals($aanvang, $wedstrijdResource[0]["aanvang"]);
    }

    /** @test  */
    public function heeftEenWedstrijdtypeId()
    {
        $wedstrijdtype = bewaarWedstrijdtype();
        bewaarWedstrijd(['wedstrijdtype_id' => $wedstrijdtype->id]);

        $wedstrijdResource = WedstrijdResource::collection(Wedstrijd::first()->get())->resolve();

        $this->assertEquals($wedstrijdtype->id, $wedstrijdResource[0]["wedstrijdtype_id"]);
    }

    /** @test  */
    public function heeftOpmerkingen()
    {
        $opmerkingen = 'OPMERKINGEN';
        bewaarWedstrijd(['opmerkingen' => $opmerkingen]);

        $wedstrijdResource = WedstrijdResource::collection(Wedstrijd::first()->get())->resolve();

        $this->assertEquals($opmerkingen, $wedstrijdResource[0]["opmerkingen"]);
    }
}
