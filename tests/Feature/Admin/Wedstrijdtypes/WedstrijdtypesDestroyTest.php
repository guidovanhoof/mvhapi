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
        $data = $response->json();
        $this->assertEquals("Wedstrijdtype niet gevonden!", $data["message"]);
    }

    /** @test */
    public function wedstrijdtypeAanwezig()
    {
        $wedstrijdtype = bewaarWedstrijdtype();

        $response = $this->deleteWedstrijdtype($wedstrijdtype->id);

        $response->assertStatus(200);
        $data = $response->json();
        $this
            ->assertDatabaseMissing(
                "wedstrijdtypes",
                ["id" => $wedstrijdtype->id, "omschrijving" => $wedstrijdtype->omschrijving]
            )
            ->assertEquals("Wedstrijdtype verwijderd!", $data["message"])
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
