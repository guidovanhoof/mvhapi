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
        $data = $response->json();
        $this->assertEquals("Plaats niet gevonden!", $data["message"]);
    }

    /** @test */
    public function reeksAanwezig()
    {
        $plaats = bewaarPlaats();

        $response = $this->verwijderPlaats($plaats->id);

        $response->assertStatus(200);
        $data = $response->json();
        $this
            ->assertDatabaseMissing(
                "plaatsen",
                plaatsToArray($plaats)
            )
            ->assertEquals("Plaats verwijderd!", $data["message"])
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
