<?php

namespace Tests\Feature\Admin\Plaatsen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GewichtenShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenGewichtAanwezig()
    {
        $response = $this->getGewicht(666);

        $response->assertStatus(404);
        $errorMessage = $response->json()["message"];
        $this->assertEquals("Gewicht niet gevonden!", $errorMessage);
    }

    /** @test */
    public function gewichtAanwezig()
    {
        $gewicht = bewaarGewicht();

        $response = $this->getGewicht($gewicht->id);

        $response->assertStatus(200);
        $data = $response->json();
        assertGewichtEquals($this, $data, $gewicht);
    }

    /**
     * @param int $id
     * @return TestResponse
     */
    private function getGewicht(int $id): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_GEWICHTEN_ADMIN . $id
            )
            ;
    }
}
