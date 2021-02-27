<?php

namespace Tests\Unit\Jeugdcategorie;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JeugdcategorieTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $jeugdcategorie;


    public function setUp(): void
    {
        parent::setUp();

        $this->jeugdcategorie = maakJeugdcategorie();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->jeugdcategorie = null;

        parent::tearDown();
    }

    /** @test  */
    public function heeftEenOmschrijving()
    {
        $omschrijving = 'jeugdcategorieomschrijving';
        $this->jeugdcategorie->omschrijving = $omschrijving;

        $this->bewaarJeugdcategorie();

        $this->assertEquals(strtoupper(strtoupper($omschrijving)), $this->jeugdcategorie->omschrijving);
    }

    /** @test  */
    public function omschrijvingIsInHoofdletters()
    {
        $omschrijvingKleineLetters = "wedstrijd";
        $omschrijvingHoofdletters = "WEDSTRIJD";
        $this->jeugdcategorie->omschrijving = $omschrijvingKleineLetters;

        $this->bewaarJeugdcategorie();

        $this->assertEquals($omschrijvingHoofdletters, $this->jeugdcategorie->omschrijving);
    }

    /** @test  */
    public function omschrijvingHeeftGeenAccenten()
    {
        $omschrijving = "áéýúíóàèùìòâêûîô^´`¨äëÿüïö~ãõñç";
        $letters = "AEYUIOAEUIOAEUIOAEYUIOAONC";
        $this->jeugdcategorie->omschrijving = $omschrijving;


        $this->bewaarJeugdcategorie();

        $this->assertEquals($letters, $this->jeugdcategorie->omschrijving);
    }

    /** @test  */
    public function omschrijvingIsUniek()
    {
        $this->expectException(QueryException::class);

        $omschrijving = "wedstrijdtype";

        bewaarJeugdcategorie(['omschrijving' => $omschrijving]);
        bewaarJeugdcategorie(['omschrijving' => $omschrijving]);
    }

    private function bewaarJeugdcategorie()
    {
        $this->jeugdcategorie->save();
        $this->jeugdcategorie->fresh();
    }
}
