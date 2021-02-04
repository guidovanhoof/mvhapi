<?php

namespace Tests\Feature\Admin\Kalenders;

use App\Models\Kalender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class KalendersDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function kalenderNietAanwezig()
    {
        $response = $this->deleteKalender(1900);

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
                $this->dataToArray($kalender)
            )
            ->assertEquals("Kalender verwijderd!", $data["message"])
        ;
    }

    /** @test */
    public function nogWedstrijdenGekoppeld()
    {
        $expectedMessage = "Kalender niet verwijderd! Nog wedstrijden aanwezig!";
        $kalender = bewaarKalender();
        bewaarWedstrijd(["kalender_id" => $kalender->id]);

        $response = $this->deleteKalender($kalender->jaar);

        $response->assertStatus(403);
        $data = $response->json();
        $this
            ->assertDatabaseHas(
                "kalenders",
                $this->dataToArray($kalender)
            )
            ->assertEquals($expectedMessage, $data["message"])
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

    /**
     * @param Kalender $kalender
     * @return array
     */
    private function dataToArray(Kalender $kalender): array
    {
        return [
            "jaar" => $kalender->jaar,
            "opmerkingen" => $kalender->opmerkingen
        ];
    }
}
