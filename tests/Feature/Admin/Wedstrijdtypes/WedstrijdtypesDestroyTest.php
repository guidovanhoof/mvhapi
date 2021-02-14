<?php

namespace Tests\Feature\Admin\Wedstrijdtypes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdtypesDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function wedstrijdtypeNietAanwezig()
    {
        $response = $this->deleteWedstrijdtype(666);

        $response->assertStatus(404);
        $errorMessage = $response->json()["message"];
        $this->assertEquals("Wedstrijdtype niet gevonden!", $errorMessage);
    }

    /** @test */
    public function wedstrijdtypeAanwezig()
    {
        $wedstrijdtype = bewaarWedstrijdtype();

        $response = $this->deleteWedstrijdtype($wedstrijdtype->id);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "wedstrijdtypes",
                ["id" => $wedstrijdtype->id, "omschrijving" => $wedstrijdtype->omschrijving]
            )
            ->assertEquals("Wedstrijdtype verwijderd!", $errorMessage)
        ;
    }

    /** @test */
    public function nogWedstrijdenGekoppeld()
    {
        $expectedMessage = "Wedstrijdtype niet verwijderd! Nog wedstrijden aanwezig!";
        $wedstrijdtype = bewaarWedstrijdtype();
        bewaarWedstrijd(["wedstrijdtype_id" => $wedstrijdtype->id]);

        $response = $this->deleteWedstrijdtype($wedstrijdtype->id);

        $response->assertStatus(403);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseHas(
                "wedstrijdtypes",
                ["id" => $wedstrijdtype->id, "omschrijving" => $wedstrijdtype->omschrijving]
            )
            ->assertEquals($expectedMessage, $errorMessage)
        ;
    }

    /**
     * @param $id
     * @return TestResponse
     */
    public function deleteWedstrijdtype($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_WEDSTRIJDTYPES_ADMIN . $id,
                )
        ;
    }
}
