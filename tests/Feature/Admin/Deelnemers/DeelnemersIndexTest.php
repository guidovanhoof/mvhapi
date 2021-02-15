<?php

namespace Tests\Feature\Admin\Deelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeelnemersIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenDeelnemersAanwezig()
    {
        $response = $this->getDeelnemers();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function deelnemersAanwezig()
    {
        $deelnemer = bewaarDeelnemer();

        $response = $this->getDeelnemers();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertDeelnemerEquals($this, $data[0], $deelnemer);
    }

    /**
     * @return TestResponse
     */
    private function getDeelnemers(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_DEELNEMERS_ADMIN
            )
        ;
    }
}
