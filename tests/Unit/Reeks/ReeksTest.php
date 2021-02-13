<?php

namespace Tests\Unit\Reeks;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReeksTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $kalender;
    private $wedstrijd;
    private $reeks;

    public function setUp(): void
    {
        parent::setUp();

        $this->kalender = bewaarKalender();
        $this->wedstrijd = bewaarWedstrijd(["kalender_id" => $this->kalender->id]);
        $this->reeks = maakReeks(["wedstrijd_id" => $this->wedstrijd->id]);
    }

    public function tearDown(): void
    {
        cleanUpDb("plaatsen");
        $this->kalender = null;
        $this->wedstrijd = null;
        $this->reeks = null;

        parent::tearDown();
    }

    /** @test  */
    public function heeftEenWedstrijdId()
    {
        $this->reeks->wedstrijd_id = $this->wedstrijd->id;

        $this->bewaarReeks();

        $this->assertEquals($this->reeks->fresh()->wedstrijd_id, $this->wedstrijd->id);
    }

    /** @test  */
    public function heeftEenForeignKeyNaarWedstrijd()
    {
        $this->expectException(QueryException::class);

        $this->reeks->wedstrijd_id = 666;

        $this->bewaarReeks();
    }

    /** @test  */
    public function heeftEenNummer()
    {
        $expectedNummer = 66;
        $this->reeks->nummer = $expectedNummer;

        $this->bewaarReeks();

        $this->assertEquals($expectedNummer, $this->reeks->nummer);
    }

    /** @test  */
    public function heeftEenAanvang()
    {
        $expectedAanvang = '13:30:00';
        $this->reeks->aanvang = $expectedAanvang;

        $this->bewaarReeks();

        $this->assertEquals($expectedAanvang, $this->reeks->aanvang);
    }

    /** @test  */
    public function heeftEenDuur()
    {
        $expectedDuur = '01:30:00';
        $this->reeks->duur = $expectedDuur;

        $this->bewaarReeks();

        $this->assertEquals($expectedDuur, $this->reeks->duur);
    }

    /** @test  */
    public function duurIsOptioneel()
    {
        $expectedDuur = null;
        $this->reeks->duur = $expectedDuur;

        $this->bewaarReeks();

        $this->assertEquals($expectedDuur, $this->reeks->duur);
    }

    /** @test  */
    public function heeftEenGewichtZak()
    {
        $expectedGewichtZak = 666;
        $this->reeks->gewicht_zak = $expectedGewichtZak;

        $this->bewaarReeks();

        $this->assertEquals($expectedGewichtZak, $this->reeks->gewicht_zak);
    }

    /** @test  */
    public function heeftOpmerkingen()
    {
        $expectedOpmerkingen = 'opmerkingen';
        $this->reeks->opmerkingen = $expectedOpmerkingen;

        $this->bewaarReeks();

        $this->assertEquals($expectedOpmerkingen, $this->reeks->opmerkingen);
    }

    /** @test  */
    public function opmerkingenIsOptioneel()
    {
        $expectedOpmerkingen = null;
        $this->reeks->opmerkingen = $expectedOpmerkingen;

        $this->bewaarReeks();

        $this->assertEquals($expectedOpmerkingen, $this->reeks->opmerkingen);
    }

    /** @test  */
    public function nummerIsUniekPerWedstrijd()
    {
        $this->expectException(QueryException::class);

        $this->bewaarReeks();
        bewaarReeks(
            [
                "wedstrijd_id" => $this->wedstrijd->id,
                "nummer" => $this->reeks->nummer,
            ]
        );
    }

    /** @test  */
    public function heeftPlaatsen()
    {
        $this->bewaarReeks();
        $expectPlaats = bewaarPlaats(["reeks_id" => $this->reeks->id]);

        $actualPlaatsen = $this->reeks->plaatsen;

        $this->assertCount(1, $actualPlaatsen);
        assertPlaatsEquals($this, $actualPlaatsen[0], $expectPlaats);
    }

    private function bewaarReeks(): void
    {
        $this->reeks->save();
        $this->reeks->fresh();
    }
}
