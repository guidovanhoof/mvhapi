<?php

namespace Tests\Unit\WedstrijddeelnemerJeugdcategorie;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WedstrijddeelnemerJeugdcategorieTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $wedstrijddeelnemerJeugdcategorie;


    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijddeelnemerJeugdcategorie = maakWedstrijddeelnemerJeugdcategorie();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->wedstrijddeelnemerJeugdcategorie = null;

        parent::tearDown();
    }

    /** @test  */
    public function heeftEenWedstrijddeelnemerId()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $this->wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id = $wedstrijddeelnemer->id;

        $this->bewaarWedstrijddeelnemerJeugdcategorie();

        $this->assertEquals(
            $wedstrijddeelnemer->id, $this->wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id
        );
    }

    /** @test  */
    public function wedstrijddeelnemerIdIsUniek()
    {
        $this->expectException(QueryException::class);

        $wedstrijddeelnemerJeugdcategorie = bewaarWedstrijddeelnemerJeugdcategorie();
        $this->wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id =
            $wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id;

        $this->bewaarWedstrijddeelnemerJeugdcategorie();
    }

    /** @test  */
    public function heeftEenJeugdcategorieId()
    {
        $jeugdcategorie = bewaarJeugdcategorie();
        $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id = $jeugdcategorie->id;

        $this->bewaarWedstrijddeelnemerJeugdcategorie();

        $this->assertEquals(
            $jeugdcategorie->id, $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id
        );
    }

    /** @test  */
    public function heeftEenForeignKeyNaarWedstrijddeelnemers()
    {
        $this->expectException(QueryException::class);
        $this->wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id = 666;

        $this->bewaarWedstrijddeelnemerJeugdcategorie();
    }

    /** @test  */
    public function heeftEenForeignKeyNaarJeugdcategorieen()
    {
        $this->expectException(QueryException::class);
        $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id = 666;

        $this->bewaarWedstrijddeelnemerJeugdcategorie();
    }

    /** @test  */
    public function heeftEenJeugdcategorie()
    {
        $jeugdcategorie = bewaarJeugdcategorie();
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $this->wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id = $wedstrijddeelnemer->id;
        $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id = $jeugdcategorie->id;
        $this->bewaarWedstrijddeelnemerJeugdcategorie();

        $actualJeugdcategorie = $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie;

        assertJeugdcategorieEquals($this, $jeugdcategorie, $actualJeugdcategorie);
    }

    private function bewaarWedstrijddeelnemerJeugdcategorie()
    {
        $this->wedstrijddeelnemerJeugdcategorie->save();
        $this->wedstrijddeelnemerJeugdcategorie->fresh();
    }
}
