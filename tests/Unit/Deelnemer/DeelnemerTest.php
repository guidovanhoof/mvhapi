<?php

namespace Tests\Unit\Deelnemer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeelnemerTest extends TestCase
{
    use RefreshDatabase;

    const NAAM_VOORNAAM = "NAAM VOORNAAM";

    /**
     * @var Collection|Model|mixed
     */
    private $deelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->deelnemer = maakDeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb("deelnemers");
        parent::tearDown();
    }

    /** @test  */
    public function heeftEenNummer()
    {
        $this->deelnemer->nummer = 666;

        $this->bewaarDeelnemer();

        assertDeelnemerInDatabase($this, $this->deelnemer);
    }

    /** @test  */
    public function nummerIsUniek()
    {
        $this->expectException(QueryException::class);

        $deelnemer = bewaarDeelnemer(["nummer" => 666]);
        $this->deelnemer->nummer = $deelnemer->nummer;

        $this->bewaarDeelnemer();
    }

    /** @test  */
    public function heefNummerRouteKeyName()
    {
        $this->assertEquals("nummer", $this->deelnemer->getRouteKeyName());
    }

    /** @test  */
    public function heeftEenNaam()
    {
        $this->deelnemer->naam = self::NAAM_VOORNAAM;

        $this->bewaarDeelnemer();

        assertDeelnemerInDatabase($this, $this->deelnemer);
    }

    /** @test  */
    public function naamIsUniek()
    {
        $this->expectException(QueryException::class);

        $deelnemer = bewaarDeelnemer(["naam" => self::NAAM_VOORNAAM]);
        $this->deelnemer->naam = $deelnemer->naam;

        $this->bewaarDeelnemer();
    }

    /** @test  */
    public function naamIsInHoofdletters()
    {
        $this->deelnemer->naam = "naam voornaam";

        $this->bewaarDeelnemer();

        $this->assertEquals(self::NAAM_VOORNAAM, $this->deelnemer->naam);
        assertDeelnemerInDatabase($this, $this->deelnemer);
    }

    /** @test  */
    public function naamHeeftGeenAccenten()
    {
        $this->deelnemer->naam = "áéýúíóàèùìòâêûîô^´`¨äëÿüïö~ãõñç";
        $expectedNaam = "AEYUIOAEUIOAEUIOAEYUIOAONC";

        $this->bewaarDeelnemer();

        $this->assertEquals($expectedNaam, $this->deelnemer->naam);
        assertDeelnemerInDatabase($this, $this->deelnemer);
    }

    private function bewaarDeelnemer()
    {
        $this->deelnemer->save();
        $this->deelnemer->fresh();
    }
}
