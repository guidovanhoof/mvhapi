<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KalendersDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function kalenderNietAanwezig()
    {
        $jaar = 1900;
        $response = $this->deleteKalender($jaar);

        $response->assertStatus(404);
        $data = $response->json();
        $this->assertEquals("Kalender niet gevonden!", $data["message"]);
    }

    /** @test */
    public function kalenderAanwezig()
    {
        $kalender = bewaarKalender();

        $response = $this->deleteKalender($kalender->jaar);

        $response->assertStatus(200);
        $data = $response->json();
        $this
            ->assertDatabaseMissing(
                "kalenders",
                ["jaar" => $kalender->jaar, "opmerkingen" => $kalender->opmerkingen]
            )
            ->assertEquals("Kalender verwijderd!", $data["message"])
        ;
    }

    /**
     * @param $jaar
     * @return \Illuminate\Testing\TestResponse
     */
    public function deleteKalender($jaar): \Illuminate\Testing\TestResponse
    {
        return $this->json(
            'DELETE',
            ADMIN_API_URL . '/' . $jaar,
        );
    }
}
