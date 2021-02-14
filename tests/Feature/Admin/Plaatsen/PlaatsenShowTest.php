<?php

namespace Tests\Feature\Admin\Plaatsen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsenShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenPlaatsenAanwezig()
    {
        $response = $this->getPlaats(666);

        $response->assertStatus(404);
        $errorMessage = $response->json()["message"];
        $this->assertEquals("Plaats niet gevonden!", $errorMessage);
    }

    /** @test */
    public function plaatsenAanwezig()
    {
        $plaats = bewaarPlaats();

        $response = $this->getPlaats($plaats->id);

        $response->assertStatus(200);
        $data = $response->json();
        assertPlaatsEquals($this, $data, $plaats);
    }

    /**
     * @param int $id
     * @return TestResponse
     */
    private function getPlaats(int $id): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_PLAATSEN_ADMIN . $id
            )
            ;
    }
}
