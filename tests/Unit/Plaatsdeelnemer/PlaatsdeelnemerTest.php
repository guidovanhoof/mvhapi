<?php

namespace Tests\Unit\Plaatsdeelnemer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaatsdeelnemerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $plaatsdeelnemer;


    public function setUp(): void
    {
        parent::setUp();

        $this->plaatsdeelnemer = maakPlaatsdeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->plaatsdeelnemer = null;

        parent::tearDown();
    }

    /** @test  */
    public function heeftEenPlaatsId()
    {
        $plaats = bewaarPlaats();
        $this->plaatsdeelnemer->plaats_id = $plaats->id;

        $this->bewaarPlaatsdeelnemer();

        assertPlaatsdeelnemerInDatabase($this, $this->plaatsdeelnemer);
    }

    /** @test  */
    public function heeftEenWedstrijddeelnemerId()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $this->plaatsdeelnemer->wedstrijddeelnemer_id = $wedstrijddeelnemer->id;

        $this->bewaarPlaatsdeelnemer();

        assertPlaatsdeelnemerInDatabase($this, $this->plaatsdeelnemer);
    }

    /** @test  */
    public function kanWegerZijn()
    {
        $this->plaatsdeelnemer->is_weger = 1;

        $this->bewaarPlaatsdeelnemer();

        assertPlaatsdeelnemerInDatabase($this, $this->plaatsdeelnemer);
    }

    /** @test */
    public function heeftEenForeignKeyNaarPlaatsen()
    {
        $this->expectException(QueryException::class);
        $this->plaatsdeelnemer->plaats_id = 666;

        $this->bewaarPlaatsdeelnemer();
    }

    /** @test */
    public function heeftEenForeignKeyNaarWedstrijddeelnemers()
    {
        $this->expectException(QueryException::class);
        $this->plaatsdeelnemer->wedstrijddeelnemer_id = 666;

        $this->bewaarPlaatsdeelnemer();
    }

    /** @test  */
    public function deelnemerIsUniekPerPlaats()
    {
        $this->expectException(QueryException::class);

        $this->bewaarPlaatsdeelnemer();
        bewaarWedstrijddeelnemer(
            [
                "plaats_id" => $this->plaatsdeelnemer->plaats_id,
                "wedstrijddeelnemer_id" => $this->plaatsdeelnemer->wedstrijddeelnemer_id,
            ]
        );
    }

    private function bewaarPlaatsdeelnemer()
    {
        $this->plaatsdeelnemer->save();
        $this->plaatsdeelnemer->fresh();
    }
}
