<?php

namespace Tests\Feature\Admin\Plaatsen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsenIndexTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb("plaatsen");

        parent::tearDown();
    }

    /** @test */
    public function geenPlaatsenAanwezig()
    {
        $response = $this->getPlaatsen();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function plaatsenAanwezig()
    {
        $plaats = bewaarPlaats();

        $response = $this->getPlaatsen();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertPlaatsEquals($this, $data[0], $plaats);
    }

    /** @test */
    public function gesorteerdOpNummerPerReeks()
    {
        $eerste_plaats = bewaarPlaats();
        $tweede_plaats = bewaarPlaats(
            [
                "reeks_id" => $eerste_plaats->reeks_id,
                "nummer" => $eerste_plaats->nummer - 1,
            ]
        );

        $response = $this->getPlaatsen();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(2, $data);
        assertPlaatsEquals($this, $data[0], $tweede_plaats);
        assertPlaatsEquals($this, $data[1], $eerste_plaats);
    }

    /**
     * @return TestResponse
     */
    private function getPlaatsen(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_PLAATSEN_ADMIN
            )
            ;
    }
}
