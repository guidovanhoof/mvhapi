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
        $this
            ->assertDatabaseHas(
                'kalenders',
                $kalender->toArray()
            )
            ->assertJson($kalender->toJson())
        ;
    }

    /** @test */
    public function jaarIsVerplicht() {
        $expectedErrorMessage = "Jaar is verplicht!";
        $kalender = maakKalender(['jaar' => null]);

        $response = $this->bewaarKalender($kalender);

        $response->assertStatus(422);
        $this->assertEquals(errorMessage("jaar", $response), $expectedErrorMessage);
    }

    /** @test */
    public function jaarIsUniek() {
        $expectedErrorMessage = "Jaar bestaat reeds!";
        $kalender = bewaarKalender();

        $response = $this->bewaarKalender($kalender);

        $response->assertStatus(422);
        $this->assertEquals(errorMessage("jaar", $response), $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $kalender = maakKalender(["opmerkingen" => null]);

        $response = $this->bewaarKalender($kalender);

        $response->assertStatus(201);
        $this
            ->assertDatabaseHas(
                'kalenders',
                $kalender->toArray()
            )
            ->assertJson($kalender->toJson())
        ;
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
}
