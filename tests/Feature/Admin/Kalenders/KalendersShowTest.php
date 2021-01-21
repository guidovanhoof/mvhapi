<?php

namespace Tests\Feature\Admin\Kalenders;

use App\Models\Kalender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class KalendersShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function kalenderNietAanwezig()
    {
        $response = $this->getKalender('1900');

        $response->assertStatus(404);
        $data = $response->json();
        $this->assertEquals("Kalender niet gevonden!", $data["message"]);
    }

    /** @test */
    public function kalenderAanwezig()
    {
        $kalender = bewaarKalender();

        $response = $this->getKalender($kalender->jaar);

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

    /**
     * @param $jaar
     * @return TestResponse
     */
    private function getKalender($jaar): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'GET',
                    URL_KALENDERS_ADMIN . $jaar
                )
        ;
    }
}
