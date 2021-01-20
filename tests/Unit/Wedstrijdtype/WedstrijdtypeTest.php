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
        $omschrijving = "nieuw wedstrijdtype";
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
    public function uniekIsNietHoofdletterGevoelig()
    {
        $this->expectException(QueryException::class);

        $omschrijving1 = "wedstrijdtype";
        $omschrijving2 = "WEDSTRIJDTYPE";

        bewaarWedstrijdtype(['omschrijving' => $omschrijving1]);
        bewaarWedstrijdtype(['omschrijving' => $omschrijving2]);
    }
}
