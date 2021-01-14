<?php

namespace Tests\Feature\Admin;

use App\Models\Kalender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KalendersIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenKalendersAanwezig()
    {
        $response = $this->get('api/admin/kalenders');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function eenKalenderAanwezig()
    {
        $kalender = bewaarKalender();

        $response = $this->get('api/admin/kalenders');

        $response->assertStatus(200);
        $data = $response->json()["data"];
        $this->assertCount(1, $data);
        $this->assertKalenderEquals($data[0], $kalender);
    }
//
//    /** @test */
//    public function meerdereKalendersAanwezig()
//    {
//        $eerste_kalender = bewaarKalender();
//        $tweede_kalender = bewaarKalender();
//
//        $response = $this->get('api/admin/kalenders');
//
//        $response->assertStatus(200);
//        $data = $response->json()["data"];
//        $this->assertCount(2, $data);
//        $this->assertKalenderEquals($data[0], $eerste_kalender);
//        $this->assertKalenderEquals($data[1], $tweede_kalender);
//    }

    /**
     * @param $data
     * @param Kalender $kalender
     */
    public function assertKalenderEquals($data, Kalender $kalender): void
    {
        $this->assertEquals($data["jaar"], $kalender->jaar);
        $this->assertEquals($data["omschrijving"], $kalender->omschrijving());
        $this->assertEquals($data["opmerkingen"], $kalender->opmerkingen);
    }

    /** @test */
    public function gesorteerdOpJaarAflopend()
    {
        $eerste_kalender = bewaarKalender(["jaar" => 2019]);
        $tweede_kalender = bewaarKalender(["jaar" => 2020]);

        $response = $this->get('api/admin/kalenders');

        $response->assertStatus(200);
        $data = $response->json()["data"];
        $this->assertCount(2, $data);
        $this->assertKalenderEquals($data[0], $tweede_kalender);
        $this->assertKalenderEquals($data[1], $eerste_kalender);
    }
}
