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

        assertNietGevonden($this, $response, "Kalender");
    }

    /** @test */
    public function kalenderAanwezig()
    {
        $kalender = bewaarKalender();

        $response = $this->deleteKalender($kalender->jaar);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "kalenders",
                kalenderToArray($kalender)
            )
            ->assertEquals("Kalender verwijderd!", $errorMessage)
        ;
    }

    /** @test */
    public function nogWedstrijdenGekoppeld()
    {
        $expectedMessage = "Kalender niet verwijderd! Nog wedstrijden aanwezig!";
        $kalender = bewaarKalender();
        bewaarWedstrijd(["kalender_id" => $kalender->id]);

        $response = $this->deleteKalender($kalender->jaar);

        $response->assertStatus(405);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseHas(
                "kalenders",
                kalenderToArray($kalender)
            )
            ->assertEquals($expectedMessage, $errorMessage)
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
