<?php

namespace Tests\Feature\Admin\Gewichten;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GewichtenIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenGewichtenAanwezig()
    {
        $response = $this->getGewichten();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function gewichtenAanwezig()
    {
        $gewicht = bewaarGewicht();

        $response = $this->getGewichten();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertGewichtEquals($this, $data[0], $gewicht);
    }

    /**
     * @return TestResponse
     */
    private function getGewichten(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_GEWICHTEN_ADMIN
            )
        ;
    }
}
