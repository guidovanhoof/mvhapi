<?php

namespace Tests\Feature\Admin\Gewichten;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GewichtenDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gewichtNietAanwezig()
    {
        $response = $this->verwijderGewicht(666);

        assertNietGevonden($this, $response, "Gewicht");
    }

    /** @test */
    public function gewichtAanwezig()
    {
        $gewicht = bewaarGewicht();

        $response = $this->verwijderGewicht($gewicht->id);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "gewichten",
                gewichtToArry($gewicht)
            )
            ->assertEquals("Gewicht verwijderd!", $errorMessage)
        ;
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function verwijderGewicht($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_GEWICHTEN_ADMIN . $id
                )
            ;
    }
}
