<?php

namespace Tests\Feature\Admin\GetrokkenMaten;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetrokkenMatenShowTest extends TestCase
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
        $response = $this->getGetrokkenMaat(666);

        assertNietGevonden($this, $response, 'GetrokkenMaat');
    }

    /** @test */
    public function getrokkenMatenAanwezig()
    {
        $getrokkenMaat = bewaarGetrokkenMaat();

        $response = $this->getGetrokkenMaat($getrokkenMaat->id);

        $response->assertStatus(200);
        $data = $response->json();
        assertGetrokkenMaatEquals($this, $data, $getrokkenMaat);
    }

    /**
     * @param int $id
     * @return TestResponse
     */
    private function getGetrokkenMaat(int $id): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_GETROKKENMATEN_ADMIN . $id
            )
        ;
    }
}
