<?php

namespace Tests\Unit\Wedstrijddeelnemer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WedstrijddeelnemerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $wedstrijddeelnemer;


    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijddeelnemer = maakWedstrijddeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->wedstrijddeelnemer = null;

        parent::tearDown();
    }

    /** @test  */
    public function heeftEenWedstrijdId()
    {
        $wedstrijd = bewaarWedstrijd();
        $this->wedstrijddeelnemer->wedstrijd_id = $wedstrijd->id;

        $this->bewaarWedstrijddeelnemer();

        assertWedstrijddeelnemerInDatabase($this, $this->wedstrijddeelnemer);
    }

    /** @test  */
    public function heeftEenDeelnemerId()
    {
        $deelnemer = bewaarDeelnemer();
        $this->wedstrijddeelnemer->deelnemer_id = $deelnemer->id;

        $this->bewaarWedstrijddeelnemer();

        assertWedstrijddeelnemerInDatabase($this, $this->wedstrijddeelnemer);
    }

    /** @test  */
    public function heeftEenDiskwalificatie()
    {
        $this->wedstrijddeelnemer->is_gediskwalificeerd = 1;

        $this->bewaarWedstrijddeelnemer();

        assertWedstrijddeelnemerInDatabase($this, $this->wedstrijddeelnemer);
    }

    /** @test  */
    public function heeftOpmerkingen()
    {
        $this->wedstrijddeelnemer->opmerkingen = "opmerkingen";

        $this->bewaarWedstrijddeelnemer();

        assertWedstrijddeelnemerInDatabase($this, $this->wedstrijddeelnemer);
    }

    /** @test  */
    public function opmerkingenIsOptioneel()
    {
        $this->wedstrijddeelnemer->opmerkingen = null;

        $this->bewaarWedstrijddeelnemer();

        assertWedstrijddeelnemerInDatabase($this, $this->wedstrijddeelnemer);
    }

    /** @test */
    public function heeftEenForeignKeyNaarWedstrijden()
    {
        $this->expectException(QueryException::class);
        $this->wedstrijddeelnemer->wedstrijd_id = 666;

        $this->bewaarWedstrijddeelnemer();
    }

    /** @test */
    public function heeftEenForeignKeyNaarDeelnemers()
    {
        $this->expectException(QueryException::class);
        $this->wedstrijddeelnemer->deelnemer_id = 666;

        $this->bewaarWedstrijddeelnemer();
    }

    /** @test  */
    public function deelnemerIsUniekPerWedstrijd()
    {
        $this->expectException(QueryException::class);

        $this->bewaarWedstrijddeelnemer();
        bewaarWedstrijddeelnemer(
            [
                "wedstrijd_id" => $this->wedstrijddeelnemer->wedstrijd_id,
                "deelnemer_id" => $this->wedstrijddeelnemer->wedstrijd_id,
            ]
        );
    }

    /** @test */
    public function heeftEenJeugdcategorie()
    {
        $this->bewaarWedstrijddeelnemer();
        $jeugdcategorie = bewaarJeugdcategorie();
        bewaarWedstrijddeelnemerJeugdcategorie(
            [
                'wedstrijddeelnemer_id' => $this->wedstrijddeelnemer->id,
                'jeugdcategorie_id' => $jeugdcategorie->id,
            ]
        );

        $actualJeugdCategorie = $this->wedstrijddeelnemer->jeugdcategorie;

        $this->assertEquals($jeugdcategorie->id, $actualJeugdCategorie->jeugdcategorie_id);
    }

    private function bewaarWedstrijddeelnemer()
    {
        $this->wedstrijddeelnemer->save();
        $this->wedstrijddeelnemer->fresh();
    }
}
