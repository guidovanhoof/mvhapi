<?php

namespace Tests\Feature\Admin\Wedstrijden;

use App\Models\Kalender;
use App\Models\Wedstrijd;
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
    public function eenWedstrijdAanwezig()
    {
        $wedstrijd = bewaarWedstrijd();

        $response = $this->getWedstrijden();

        $response->assertStatus(200);
        $data = $response->json()["data"];
        $this->assertCount(1, $data);
        $this->assertWedstrijdEquals($data[0], $wedstrijd);
    }

    /**
     * @param $data
     * @param Kalender $wedstrijd
     */
    private function assertWedstrijdEquals($data, Wedstrijd $wedstrijd): void
    {
        $this->assertEquals($data["kalender_id"], $wedstrijd->kalender_id);
        $this->assertEquals($data["datum"], $wedstrijd->datum);
        $this->assertEquals($data["nummer"], $wedstrijd->nummer);
        $this->assertEquals($data["omschrijving"], $wedstrijd->omschrijving);
        $this->assertEquals($data["sponsor"], $wedstrijd->sponsor);
        $this->assertEquals($data["aanvang"], $wedstrijd->aanvang);
        $this->assertEquals($data["wedstrijdtype_id"], $wedstrijd->wedstrijdtype_id);
        $this->assertEquals($data["opmerkingen"], $wedstrijd->opmerkingen);
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
