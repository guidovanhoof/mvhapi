<?php

namespace Tests\Feature\Admin\Wedstrijden;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdenIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenWedstrijdenAanwezig()
    {
        $response = $this->getWedstrijden();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function wedstrijdenAanwezig()
    {
        $wedstrijd = bewaarWedstrijd();

        $response = $this->getWedstrijden();

        $response->assertStatus(200);
        $data = $response->json()["data"];
        $this->assertCount(1, $data);
//        $this->assertWedstrijdEquals($data[0], $wedstrijd);
        assertWedstrijdEquals($this, $data[0], $wedstrijd);
    }

//    /** @test */
//    public function gesorteerdOpJaarAflopend()
//    {
//        $eerste_kalender = bewaarKalender(["jaar" => 2019]);
//        $tweede_kalender = bewaarKalender(["jaar" => 2020]);
//
//        $response = $this->getWedstrijden();
//
//        $response->assertStatus(200);
//        $data = $response->json()["data"];
//        $this->assertCount(2, $data);
//        $this->assertWedstrijdEquals($data[0], $tweede_kalender);
//        $this->assertWedstrijdEquals($data[1], $eerste_kalender);
//    }

    /**
     * @return TestResponse
     */
    private function getWedstrijden(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_WEDSTRIJDEN_ADMIN
            )
        ;
    }
}
