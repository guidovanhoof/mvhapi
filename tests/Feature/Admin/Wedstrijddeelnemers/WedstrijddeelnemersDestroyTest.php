<?php

namespace Tests\Feature\Admin\Wedstrijddeelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function wedstrijddeelnemerNietAanwezig()
    {
        $response = $this->verwijderWedstrijddeelnemer(666);

        assertNietGevonden($this, $response,"Wedstrijddeelnemer");
    }

    /** @test */
    public function wedstrijddeelnemerAanwezig()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();

        $response = $this->verwijderWedstrijddeelnemer($wedstrijddeelnemer->id);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "wedstrijddeelnemers",
                wedstrijddeelnemerToArry($wedstrijddeelnemer)
            )
            ->assertEquals("Wedstrijddeelnemer verwijderd!", $errorMessage)
        ;
    }

    /** @test */
    public function nogJeugdcategorieenGekoppeld()
    {
        $expectedMessage = "Wedstrijddeelnemer niet verwijderd! Nog jeugdcategorie/getrokken maat aanwezig!";
        $jeugdcategorie = bewaarJeugdcategorie();
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        bewaarWedstrijddeelnemerJeugdcategorie(
            [
                'wedstrijddeelnemer_id' => $wedstrijddeelnemer->id,
                'jeugdcategorie_id' => $jeugdcategorie->id,
            ]
        );

        $response = $this->verwijderWedstrijddeelnemer($wedstrijddeelnemer->id);

        $response->assertStatus(405);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseHas(
                "wedstrijddeelnemers",
                wedstrijddeelnemerToArry($wedstrijddeelnemer)
            )
            ->assertEquals($expectedMessage, $errorMessage);
    }

    /** @test */
    public function nogGetrokkenMatenGekoppeld()
    {
        $expectedMessage = "Wedstrijddeelnemer niet verwijderd! Nog jeugdcategorie/getrokken maat aanwezig!";
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $getrokkenMaat = bewaarWedstrijddeelnemer();
        bewaarGetrokkenMaat(
            [
                'wedstrijddeelnemer_id' => $wedstrijddeelnemer->id,
                'getrokken_maat_id' => $getrokkenMaat->id,
            ]
        );

        $response = $this->verwijderWedstrijddeelnemer($wedstrijddeelnemer->id);

        $response->assertStatus(405);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseHas(
                "wedstrijddeelnemers",
                wedstrijddeelnemerToArry($wedstrijddeelnemer)
            )
            ->assertEquals($expectedMessage, $errorMessage);
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function verwijderWedstrijddeelnemer($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_WEDSTRIJDDEELNEMERS_ADMIN . $id
                )
            ;
    }
}
