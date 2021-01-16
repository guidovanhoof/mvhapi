<?php

namespace Tests\Feature\Admin;

use App\Models\Kalender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KalendersUpdateTest extends TestCase
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
    public function jaarWijzigen()
    {
        $kalender = bewaarKalender();
        $jaar = $kalender->jaar;
        $kalender->jaar = $kalender->jaar + 10;

        $response = $this->updateKalender($jaar, $kalender);

        $response->assertStatus(200);
        $this->assertKalenderInDatabase($kalender);
    }

    /** @test */
    public function opmerkingenWijzigen()
    {
        $kalender = bewaarKalender();
        $kalender->opmerkingen = "Gewijzigde opmerkingen";

        $response = $this->updateKalender($kalender->jaar, $kalender);

        $response->assertStatus(200);
        $this->assertKalenderInDatabase($kalender);
    }

    /** @test */
    public function jaarEnOpmerkingenWijzigen()
    {
        $kalender = bewaarKalender();
        $jaar = $kalender->jaar;
        $kalender->jaar = $kalender->jaar + 10;
        $kalender->opmerkingen = "Gewijzigde opmerkingen";

        $response = $this->updateKalender($jaar, $kalender);

        $response->assertStatus(200);
        $this->assertKalenderInDatabase($kalender);
    }

    /** @test */
    public function jaarIsVerplicht() {
        $expectedErrorMessage = "Jaar is verplicht!";
        $kalender = bewaarKalender();
        $jaar = $kalender->jaar;
        $kalender->jaar = null;

        $response = $this->updateKalender($jaar, $kalender);

        $response->assertStatus(422);
        $this->assertEquals(errorMessage("jaar", $response), $expectedErrorMessage);
    }


    /** @test */
    public function jaarIsUniek() {
        $expectedErrorMessage = "Jaar bestaat reeds!";
        $kalender1 = bewaarKalender();
        $kalender2 = bewaarKalender();
        $jaar = $kalender2->jaar;
        $kalender2->jaar = $kalender1->jaar;

        $response = $this->updateKalender($jaar, $kalender2);

        $response->assertStatus(422);
        $this->assertEquals(errorMessage("jaar", $response), $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $kalender = bewaarKalender();
        $kalender->opmerkingen = null;

        $response = $this->updateKalender($kalender->jaar, $kalender);

        $response->assertStatus(200);
        $this->assertKalenderInDatabase($kalender);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $kalender
     * @return array
     */
    private function dataToArray(\Illuminate\Database\Eloquent\Model $kalender): array
    {
        return ["jaar" => $kalender->jaar, "opmerkingen" => $kalender->opmerkingen];
    }

    /**
     * @param Kalender $kalender
     */
    private function assertKalenderInDatabase(Kalender $kalender): void
    {
        $this
            ->assertDatabaseHas(
                'kalenders',
                $this->dataToArray($kalender)
            )
            ->assertJson($kalender->toJson());
    }

    /**
     * @param $jaar
     * @param Kalender $kalender
     * @return \Illuminate\Testing\TestResponse
     */
    private function updateKalender($jaar, Kalender $kalender): \Illuminate\Testing\TestResponse
    {
        return $this->json(
            'PUT',
            ADMIN_API_URL . '/' . $jaar,
            $this->dataToArray($kalender)
        );
    }
}
