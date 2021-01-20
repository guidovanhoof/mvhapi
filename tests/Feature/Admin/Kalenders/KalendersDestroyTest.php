<?php

namespace Tests\Feature\Admin\Kalenders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
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
     * @return TestResponse
     */
    public function deleteKalender($jaar): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_KALENDERS_ADMIN . $jaar,
                )
        ;
    }
}
