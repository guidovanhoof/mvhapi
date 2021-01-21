<?php

namespace Tests\Unit\Wedstrijdtype;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WedstrijdtypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function heeftEenOmschrijving()
    {
        $omschrijving = "NIEUW WEDSTRIJDTYPE";
        $wedstrijdtype = bewaarWedstrijdtype(['omschrijving' => $omschrijving]);

        $this->assertEquals($omschrijving, $wedstrijdtype->omschrijving);
    }

    /** @test  */
    public function omschrijvingIsUniek()
    {
        $this->expectException(QueryException::class);

        $omschrijving = "wedstrijdtype";

        bewaarWedstrijdtype(['omschrijving' => $omschrijving]);
        bewaarWedstrijdtype(['omschrijving' => $omschrijving]);
    }

    /** @test  */
    public function omschrijvingIsInHoofdletters()
    {
        $omschrijvingKleineLetters = "wedstrijdtype";
        $omschrijvingHoofdletters = "WEDSTRIJDTYPE";

        $wedstrijdtype = bewaarWedstrijdtype(['omschrijving' => $omschrijvingKleineLetters]);

        $this->assertEquals($omschrijvingHoofdletters, $wedstrijdtype->omschrijving);
    }

    /** @test  */
    public function enkelLettersInOmschrijving()
    {
        $omschrijving = "|@#{[^{}1234567890°_&é\"'(§è!çà)-azertyuiop^*[]qsdfghjklmùµ%£´`²³<wxcvbn,;:=>?./+~ ";
        $letters = "AZERTYUIOPQSDFGHJKLMWXCVBN ";

        $wedstrijdtype = bewaarWedstrijdtype(['omschrijving' => $omschrijving]);

        $this->assertEquals($letters, $wedstrijdtype->omschrijving);
    }
}
