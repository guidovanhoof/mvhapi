<?php

namespace Tests\Feature\Admin\Wedstrijdtypes;

use App\Models\Kalender;
use App\Models\Wedstrijdtype;
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
        $data = $response->json()["data"];
        $this->assertCount(1, $data);
        $this->assertWedstrijdtypeEquals($data[0], $wedstrijdtype);
    }

    /**
     * @param $data
     * @param Kalender $wedstrijdtype
     */
    private function assertWedstrijdtypeEquals($data, Wedstrijdtype $wedstrijdtype): void
    {
        $this->assertEquals($data["id"], $wedstrijdtype->id);
        $this->assertEquals($data["omschrijving"], $wedstrijdtype->omschrijving);
    }
//
//    /** @test */
//    public function gesorteerdOpJaarAflopend()
//    {
//        $eerste_kalender = bewaarKalender(["jaar" => 2019]);
//        $tweede_kalender = bewaarKalender(["jaar" => 2020]);
//
//        $response = $this->getWedstrijdtypes();
//
//        $response->assertStatus(200);
//        $data = $response->json()["data"];
//        $this->assertCount(2, $data);
//        $this->assertKalenderEquals($data[0], $tweede_kalender);
//        $this->assertKalenderEquals($data[1], $eerste_kalender);
//    }

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
