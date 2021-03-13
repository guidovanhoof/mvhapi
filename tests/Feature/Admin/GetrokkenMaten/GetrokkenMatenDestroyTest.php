<?php

namespace Tests\Feature\Admin\GetrokkenMaten;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetrokkenMatenDestroyTest extends TestCase
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
        $response = $this->verwijderGetrokkenMaat(666);

        assertNietGevonden($this, $response, 'GetrokkenMaat');
    }

    /** @test */
    public function getrokkenMatenAanwezig()
    {
        $getrokkenMaat = bewaarGetrokkenMaat();

        $response = $this->verwijderGetrokkenMaat($getrokkenMaat->id);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "getrokkenmaten",
                getrokkenMaatToArray($getrokkenMaat)
            )
            ->assertEquals("GetrokkenMaat verwijderd!", $errorMessage)
        ;
    }

    /**
     * @param int $id
     * @return TestResponse
     */
    private function verwijderGetrokkenMaat(int $id): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'DELETE',
                URL_GETROKKENMATEN_ADMIN . $id
            )
        ;
    }
}
