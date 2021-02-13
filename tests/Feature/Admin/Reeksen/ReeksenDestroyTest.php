<?php

namespace Tests\Feature\Admin\Reeksen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ReeksenDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reeksNietAanwezig()
    {
        $response = $this->verwijderReeks(666);

        $response->assertStatus(404);
        $data = $response->json();
        $this->assertEquals("Reeks niet gevonden!", $data["message"]);
    }

    /** @test */
    public function reeksAanwezig()
    {
        $reeks = bewaarReeks();

        $response = $this->verwijderReeks($reeks->id);

        $response->assertStatus(200);
        $data = $response->json();
        $this
            ->assertDatabaseMissing(
                "reeksen",
                reeksToArray($reeks)
            )
            ->assertEquals("Reeks verwijderd!", $data["message"])
        ;
    }

    /** @test */
    public function nogPlaatsenGekoppeld()
    {
        $expectedMessage = "Reeks niet verwijderd! Nog plaatsen aanwezig!";
        $reeks = bewaarReeks();
        bewaarPlaats(["reeks_id" => $reeks->id]);

        $response = $this->verwijderReeks($reeks->id);

        $response->assertStatus(403);
        $data = $response->json();
        $this
            ->assertDatabaseHas(
                "reeksen",
                reeksToArray($reeks)
            )
            ->assertEquals($expectedMessage, $data["message"])
        ;
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function verwijderReeks($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_REEKSEN_ADMIN . $id
                )
            ;
    }
}
