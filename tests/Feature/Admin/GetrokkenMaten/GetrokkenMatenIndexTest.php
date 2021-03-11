<?php

namespace Tests\Feature\Admin\GetrokkenMaten;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetrokkenMatenIndexTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function geenGetrokkenMatenAanwezig()
    {
        $response = $this->getGetrokkenMaten();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function getrokkenMatenAanwezig()
    {
        $getrokkenMaat = bewaarGetrokkenMaat();

        $response = $this->getGetrokkenMaten();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertGetrokkenMaatEquals($this, $data[0], $getrokkenMaat);
    }

    /**
     * @return TestResponse
     */
    private function getGetrokkenMaten(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_GETROKKENMATEN_ADMIN
            )
        ;
    }
}
