<?php

namespace Tests\Feature\Admin\Reeksen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsenDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function plaatsNietAanwezig()
    {
        $response = $this->verwijderPlaats(666);

        $response->assertStatus(404);
        $errorMessage = $response->json()["message"];
        $this->assertEquals("Plaats niet gevonden!", $errorMessage);
    }

    /** @test */
    public function plaatsAanwezig()
    {
        $plaats = bewaarPlaats();

        $response = $this->verwijderPlaats($plaats->id);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "plaatsen",
                plaatsToArray($plaats)
            )
            ->assertEquals("Plaats verwijderd!", $errorMessage)
        ;
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function verwijderPlaats($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_PLAATSEN_ADMIN . $id
                )
            ;
    }
}