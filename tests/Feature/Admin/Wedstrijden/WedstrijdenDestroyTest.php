<?php

namespace Tests\Feature\Admin\Wedstrijden;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdenDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function wedstrijdNietAanwezig()
    {
        $response = $this->verwijderWedstrijd('1900-01-01');

        $response->assertStatus(404);
        $errorMessage = $response->json()["message"];
        $this->assertEquals("Wedstrijd niet gevonden!", $errorMessage);
    }

    /** @test */
    public function wedstrijdAanwezig()
    {
        $wedstrijd = bewaarWedstrijd();

        $response = $this->verwijderWedstrijd($wedstrijd->datum);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "wedstrijden",
                [
                    "kalender_id" => $wedstrijd->kalender_id,
                    "datum" => $wedstrijd->datum,
                    "omschrijving" => $wedstrijd->omschrijving,
                    "aanvang" => $wedstrijd->aanvang,
                ]
            )
            ->assertEquals("Wedstrijd verwijderd!", $errorMessage)
        ;
    }

    /** @test */
    public function nogReeksenGekoppeld()
    {
        $expectedMessage = "Wedstrijd niet verwijderd! Nog reeksen aanwezig!";
        $wedstrijd = bewaarWedstrijd();
        bewaarReeks(["wedstrijd_id" => $wedstrijd->id]);

        $response = $this->verwijderWedstrijd($wedstrijd->datum);

        $response->assertStatus(403);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseHas(
                "wedstrijden",
                wedstrijdToArray($wedstrijd)
            )
            ->assertEquals($expectedMessage, $errorMessage)
        ;
    }

    /**
     * @param $datum
     * @return TestResponse
     */
    private function verwijderWedstrijd($datum): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_WEDSTRIJDEN_ADMIN . $datum
                )
            ;
    }
}
