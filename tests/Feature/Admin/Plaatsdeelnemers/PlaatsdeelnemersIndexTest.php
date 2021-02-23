<?php

namespace Tests\Feature\Admin\Plaatsdeelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsdeelnemersIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenPlaatsdeelnemersAanwezig()
    {
        $response = $this->getPlaatsdeelnemers();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function plaatsdeelnemersAanwezig()
    {
        $plaatsdeelnemer = bewaarPlaatsdeelnemer();

        $response = $this->getPlaatsdeelnemers();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertPlaatsdeelnemerEquals($this, $data[0], $plaatsdeelnemer);
    }

    /**
     * @return TestResponse
     */
    private function getPlaatsdeelnemers(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_PLAATSDEELNEMERS_ADMIN
            )
        ;
    }
}
