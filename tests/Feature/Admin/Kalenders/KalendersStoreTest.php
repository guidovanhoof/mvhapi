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

    private $kalender;

    public function setUp(): void
    {
        parent::setUp();

        $this->kalender = maakKalender();
    }

    public function tearDown(): void
    {
        cleanUpDb("kalenders");

        parent::tearDown();
    }

    /** @test */
    public function kalenderAanmaken()
    {
        $response = $this->bewaarKalender($this->kalender);

        $response->assertStatus(201);
        $this->assertInDatabase($this->kalender);
    }

    /** @test */
    public function jaarIsVerplicht() {
        $expectedErrorMessage = "Jaar is verplicht!";
        $this->kalender->jaar = null;

        $response = $this->bewaarKalender($this->kalender);

        assertErrorMessage($this, "jaar", $response, $expectedErrorMessage);
    }

    /** @test */
    public function jaarIsUniek() {
        $expectedErrorMessage = "Jaar bestaat reeds!";
        $bestaandeKalender = bewaarKalender();
        $this->kalender->jaar = $bestaandeKalender->jaar;

        $response = $this->bewaarKalender($this->kalender);

        assertErrorMessage($this, "jaar", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $this->kalender->opmerkingen = null;

        $response = $this->bewaarKalender($this->kalender);

        $response->assertStatus(201);
        $this->assertInDatabase($this->kalender);
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
                    $this->dataToArray($kalender)
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
                $this->dataToArray($kalender)
            )
            ->assertJson($kalender->toJson());
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
