<?php

namespace Tests\Feature\Admin\Wedstrijddeelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenWedstrijddeelnemersAanwezig()
    {
        $response = $this->getWedstrijddeelnemers();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function wedstrijddeelnemersAanwezig()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();

        $response = $this->getWedstrijddeelnemers();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertWedstrijddeelnemerEquals($this, $data[0], $wedstrijddeelnemer);
    }

    /**
     * @return TestResponse
     */
    private function getWedstrijddeelnemers(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_WEDSTRIJDDEELNEMERS_ADMIN
            )
        ;
    }
}
