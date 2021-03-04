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

        $this->bewaarJeugdcategorie();

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

        $this->bewaarJeugdcategorie();
    }

    /** @test  */
    public function heeftEenJeugdcategorieId()
    {
        $jeugdcategorie = bewaarJeugdcategorie();
        $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id = $jeugdcategorie->id;

        $this->bewaarJeugdcategorie();

        $this->assertEquals(
            $jeugdcategorie->id, $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id
        );
    }

    /** @test  */
    public function heeftEenForeignKeyNaarWedstrijddeelnemers()
    {
        $this->expectException(QueryException::class);
        $this->wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id = 666;

        $this->bewaarJeugdcategorie();
    }

    /** @test  */
    public function heeftEenForeignKeyNaarJeugdcategorieen()
    {
        $this->expectException(QueryException::class);
        $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id = 666;

        $this->bewaarJeugdcategorie();
    }

    private function bewaarJeugdcategorie()
    {
        $this->wedstrijddeelnemerJeugdcategorie->save();
        $this->wedstrijddeelnemerJeugdcategorie->fresh();
    }
}
