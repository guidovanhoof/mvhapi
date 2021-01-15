<?php

namespace Tests\Feature\Admin;

use App\Models\Kalender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KalendersShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function kalenderNietAanwezig()
    {
        $response = $this->get('api/admin/kalenders/1900');

        $response->assertStatus(404);
        $data = $response->json();
        $this->assertEquals("Kalender niet gevonden!", $data["message"]);
    }

    /** @test */
    public function kalenderAanwezig()
    {
        $kalender = bewaarKalender();

        $response = $this->get('api/admin/kalenders/' . $kalender->jaar);

        $response->assertStatus(200);
        $data = $response->json()["data"];
        $this->assertKalenderEquals($data, $kalender);
    }

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
}
