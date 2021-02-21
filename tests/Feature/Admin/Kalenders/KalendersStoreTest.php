<?php

namespace Tests\Feature\Admin\Kalenders;

use App\Models\Kalender;
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
        assertKalenderInDatabase($this, $this->kalender);
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
        assertKalenderInDatabase($this, $this->kalender);
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
                    kalenderToArray($kalender)
                )
        ;
    }
}
