<?php

namespace Tests\Feature\Admin\Plaatsen;

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

        assertNietGevonden($this, $response, "Plaats");
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

    /** @test */
    public function nogGewichtenGekoppeld()
    {
        $expectedMessage = "Plaats niet verwijderd! Nog gewichten/deelnemers aanwezig!";
        $plaats = bewaarPlaats();
        bewaarGewicht(["plaats_id" => $plaats->id]);

        $response = $this->verwijderPlaats($plaats->id);

        $response->assertStatus(405);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseHas(
                "plaatsen",
                plaatsToArray($plaats)
            )
            ->assertEquals($expectedMessage, $errorMessage)
        ;
    }

    /** @test */
    public function nogDeelnemersGekoppeld()
    {
        $expectedMessage = "Plaats niet verwijderd! Nog gewichten/deelnemers aanwezig!";
        $plaats = bewaarPlaats();
        bewaarPlaatsdeelnemer(["plaats_id" => $plaats->id]);

        $response = $this->verwijderPlaats($plaats->id);

        $response->assertStatus(405);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseHas(
                "plaatsen",
                plaatsToArray($plaats)
            )
            ->assertEquals($expectedMessage, $errorMessage)
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
