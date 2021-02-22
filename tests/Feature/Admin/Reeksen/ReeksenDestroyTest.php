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

        assertNietGevonden($this, $response, "Reeks");
    }

    /** @test */
    public function reeksAanwezig()
    {
        $reeks = bewaarReeks();

        $response = $this->verwijderReeks($reeks->id);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "reeksen",
                reeksToArray($reeks)
            )
            ->assertEquals("Reeks verwijderd!", $errorMessage)
        ;
    }

    /** @test */
    public function nogPlaatsenGekoppeld()
    {
        $expectedMessage = "Reeks niet verwijderd! Nog plaatsen aanwezig!";
        $reeks = bewaarReeks();
        bewaarPlaats(["reeks_id" => $reeks->id]);

        $response = $this->verwijderReeks($reeks->id);

        $response->assertStatus(405);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseHas(
                "reeksen",
                reeksToArray($reeks)
            )
            ->assertEquals($expectedMessage, $errorMessage)
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
