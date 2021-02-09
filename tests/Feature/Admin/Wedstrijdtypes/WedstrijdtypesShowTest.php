<?php

namespace Tests\Feature\Admin\Wedstrijdtypes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdtypesShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function wedstrijdtypeNietAanwezig()
    {
        $response = $this->getWedstrijdtype('666');

        $response->assertStatus(404);
        $data = $response->json();
        $this->assertEquals("Wedstrijdtype niet gevonden!", $data["message"]);
    }

    /** @test */
    public function wedstrijdtypeAanwezig()
    {
        $wedstrijdtype = bewaarWedstrijdtype();

        $response = $this->getWedstrijdtype($wedstrijdtype->id);

        $response->assertStatus(200);
        $data = $response->json();
        assertWedstrijdtypeEquals($this, $data, $wedstrijdtype);
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function getWedstrijdtype($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'GET',
                    URL_WEDSTRIJDTYPES_ADMIN . $id
                )
        ;
    }
}
