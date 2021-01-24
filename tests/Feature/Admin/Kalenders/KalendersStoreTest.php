<?php

namespace Tests\Feature\Admin\Kalenders;

use App\Models\Kalender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class KalendersStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function kalenderAanmaken()
    {
        $kalender = maakKalender();

        $response = $this->bewaarKalender($kalender);

        $response->assertStatus(201);
        $this->assertInDatabase($kalender);
    }

    /** @test */
    public function jaarIsVerplicht() {
        $expectedErrorMessage = "Jaar is verplicht!";
        $kalender = maakKalender(['jaar' => null]);

        $response = $this->bewaarKalender($kalender);

        assertErrorMessage($this, "jaar", $response, $expectedErrorMessage);
    }

    /** @test */
    public function jaarIsUniek() {
        $expectedErrorMessage = "Jaar bestaat reeds!";
        $kalender = bewaarKalender();

        $response = $this->bewaarKalender($kalender);

        assertErrorMessage($this, "jaar", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $kalender = maakKalender(["opmerkingen" => null]);

        $response = $this->bewaarKalender($kalender);

        $response->assertStatus(201);
        $this->assertInDatabase($kalender);
    }

    /**
     * @param Kalender $kalender
     * @return TestResponse
     */
    private function bewaarKalender(Kalender $kalender): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'POST',
                    URL_KALENDERS_ADMIN,
                    ["jaar" => $kalender->jaar, "opmerkingen" => $kalender->opmerkingen]
                )
        ;
    }

    /**
     * @param Kalender $kalender
     */
    private function assertInDatabase(Kalender $kalender): void
    {
        $this
            ->assertDatabaseHas(
                'kalenders',
                $kalender->toArray()
            )
            ->assertJson($kalender->toJson());
    }
}
