<?php

namespace Tests\Feature\Admin\Kalenders;

use App\Models\Kalender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class KalendersUpdateTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function kalenderNietAanwezig()
    {
        $kalender = maakKalender(["jaar" => 1900]);

        $response = $this->wijzigKalender($kalender, $kalender->jaar);

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

        $response = $this->wijzigKalender($kalender, $jaar);

        $response->assertStatus(200);
        $this->assertKalenderInDatabase($kalender);
    }

    /** @test */
    public function opmerkingenWijzigen()
    {
        $kalender = bewaarKalender();
        $kalender->opmerkingen = "Gewijzigde opmerkingen";

        $response = $this->wijzigKalender($kalender, $kalender->jaar);

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

        $response = $this->wijzigKalender($kalender, $jaar);

        $response->assertStatus(200);
        $this->assertKalenderInDatabase($kalender);
    }

    /** @test */
    public function jaarIsVerplicht() {
        $expectedErrorMessage = "Jaar is verplicht!";
        $kalender = bewaarKalender();
        $jaar = $kalender->jaar;
        $kalender->jaar = null;

        $response = $this->wijzigKalender($kalender, $jaar);

        assertErrorMessage($this, "jaar", $response, $expectedErrorMessage);
    }


    /** @test */
    public function jaarIsUniek() {
        $expectedErrorMessage = "Jaar bestaat reeds!";
        $kalender1 = bewaarKalender();
        $kalender2 = bewaarKalender();
        $jaar = $kalender2->jaar;
        $kalender2->jaar = $kalender1->jaar;

        $response = $this->wijzigKalender($kalender2, $jaar);

        assertErrorMessage($this, "jaar", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $kalender = bewaarKalender();
        $kalender->opmerkingen = null;

        $response = $this->wijzigKalender($kalender, $kalender->jaar);

        $response->assertStatus(200);
        $this->assertKalenderInDatabase($kalender);
    }

    /**
     * @param Kalender $kalender
     * @return array
     */
    private function dataToArray(Kalender $kalender): array
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
     * @param Kalender $kalender
     * @return TestResponse
     */
    private function wijzigKalender(Kalender $kalender, $jaar): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'PUT',
                    URL_KALENDERS_ADMIN . $jaar,
                    ["jaar" => $kalender->jaar, "opmerkingen" => $kalender->opmerkingen]
                )
            ;
    }
}
