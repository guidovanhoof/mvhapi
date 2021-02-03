<?php

namespace Tests\Feature\Admin\Wedstrijdtypes;

use App\Models\Kalender;
use App\Models\Wedstrijdtype;
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
        $this->assertWedstrijdtypeEquals($data, $wedstrijdtype);
    }

    /**
     * @param $data
     * @param Wedstrijdtype $wedstrijdtype
     */
    public function assertWedstrijdtypeEquals($data, Wedstrijdtype $wedstrijdtype): void
    {
        $this->assertEquals($data["id"], $wedstrijdtype->id);
        $this->assertEquals($data["omschrijving"], $wedstrijdtype->omschrijving);
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
