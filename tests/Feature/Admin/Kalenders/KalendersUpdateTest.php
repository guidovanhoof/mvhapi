<?php

namespace Tests\Feature\Admin\Kalenders;

use App\Models\Kalender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class KalendersUpdateTest extends TestCase
{
    use RefreshDatabase;

    private $kalender;
    private $jaar;

    public function setUp(): void
    {
        parent::setUp();

        $this->kalender = bewaarKalender(["jaar" => date("Y")]);
        $this->jaar = $this->kalender->jaar;
    }

    public function tearDown(): void
    {
        cleanUpDb("kalenders");

        parent::tearDown();
    }

    /** @test */
    public function kalenderNietAanwezig()
    {
        $response = $this->wijzigKalender($this->kalender, 1900);

        assertNietGevonden($this, $response, "Kalender");
    }

    /** @test */
    public function jaarWijzigen()
    {
        $this->kalender->jaar = $this->kalender->jaar + 10;

        $response = $this->wijzigKalender($this->kalender, $this->jaar);

        $response->assertStatus(200);
        assertKalenderInDatabase($this, $this->kalender);
    }

    /** @test */
    public function opmerkingenWijzigen()
    {
        $this->kalender->opmerkingen = "Gewijzigde opmerkingen";

        $response = $this->wijzigKalender($this->kalender, $this->jaar);

        $response->assertStatus(200);
        assertKalenderInDatabase($this, $this->kalender);
    }

    /** @test */
    public function jaarEnOpmerkingenWijzigen()
    {
        $this->kalender->jaar = $this->kalender->jaar + 10;
        $this->kalender->opmerkingen = "Gewijzigde opmerkingen";

        $response = $this->wijzigKalender($this->kalender, $this->jaar);

        $response->assertStatus(200);
        assertKalenderInDatabase($this, $this->kalender);
    }

    /** @test */
    public function jaarIsVerplicht() {
        $expectedErrorMessage = "Jaar is verplicht!";
        $this->kalender->jaar = null;

        $response = $this->wijzigKalender($this->kalender, $this->jaar);

        assertErrorMessage($this, "jaar", $response, $expectedErrorMessage);
    }


    /** @test */
    public function jaarIsUniek() {
        $expectedErrorMessage = "Jaar bestaat reeds!";
        $bestaandeKalender = bewaarKalender(["jaar" => date("Y") + 1]);
        $this->kalender->jaar = $bestaandeKalender->jaar;

        $response = $this->wijzigKalender($this->kalender, $this->jaar);

        assertErrorMessage($this, "jaar", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $this->kalender->opmerkingen = null;

        $response = $this->wijzigKalender($this->kalender, $this->jaar);

        $response->assertStatus(200);
        assertKalenderInDatabase($this, $this->kalender);
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

    /**
     * @param Kalender $kalender
     * @param $jaar
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
                    kalenderToArray($kalender)
                )
            ;
    }
}
