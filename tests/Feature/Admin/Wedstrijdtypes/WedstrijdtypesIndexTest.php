<?php

namespace Tests\Feature\Admin\Wedstrijdtypes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdtypesIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenWedstrijdtypesAanwezig()
    {
        $response = $this->getWedstrijdtypes();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function eenWedstrijdtypeAanwezig()
    {
        $wedstrijdtype = bewaarWedstrijdtype();

        $response = $this->getWedstrijdtypes();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertWedstrijdtypeEquals($this, $data[0], $wedstrijdtype);
    }

    /**
     * @return TestResponse
     */
    private function getWedstrijdtypes(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_WEDSTRIJDTYPES_ADMIN
            )
        ;
    }
}
