<?php

namespace Tests\Feature\Admin\Reeksen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ReeksenIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenReeksenAanwezig()
    {
        $response = $this->getReeksen();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function eenReeksAanwezig()
    {
        $reeks = bewaarReeks();

        $response = $this->getReeksen();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertReeksEquals($this, $data[0], $reeks);
    }

    /** @test */
    public function gesorteerdOpNummerPerWedstrijd()
    {
        $eerste_reeks = bewaarReeks();
        $tweede_reeks = bewaarReeks(
            [
                "wedstrijd_id" => $eerste_reeks->wedstrijd_id,
                "nummer" => $eerste_reeks->nummer - 1,
            ]
        );

        $response = $this->getReeksen();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(2, $data);
        assertReeksEquals($this, $data[0], $tweede_reeks);
        assertReeksEquals($this, $data[1], $eerste_reeks);
    }

    /**
     * @return TestResponse
     */
    private function getReeksen(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_REEKSEN_ADMIN
            )
            ;
    }
}
