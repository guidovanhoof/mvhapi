<?php

namespace Tests\Feature\Admin\GetrokkenMaten;

use App\Models\GetrokkenMaat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetrokkenMatenUpdateTest extends TestCase
{
    use RefreshDatabase;

    private $getrokkenmaat;

    public function  setUp(): void
    {
        parent::setUp();

        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $getrokkenWedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $this->getrokkenmaat = bewaarGetrokkenMaat(
            [
                'wedstrijddeelnemer_id' => $wedstrijddeelnemer->id,
                'getrokken_maat_id' => $getrokkenWedstrijddeelnemer->id,
            ]
        );
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function geenGetrokkenMaatAanwezig()
    {
        $this->getrokkenmaat->id = 666;

        $response = $this->wijzigGetrokkenMaat(
            $this->getrokkenmaat
        );

        assertNietGevonden($this, $response, 'GetrokkenMaat');
    }

    /** @test */
    public function getrokkenMaatWijzigen()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $getrokkenmaat = bewaarWedstrijddeelnemer();
        $this->getrokkenmaat->wedstrijddeelnemer_id = $wedstrijddeelnemer->id;
        $this->getrokkenmaat->getrokken_maat_id = $getrokkenmaat->id;

        $response = $this->wijzigGetrokkenMaat(
            $this->getrokkenmaat
        );

        $response->assertStatus(200);
        assertGetrokkenMaatInDatabase(
            $this, $this->getrokkenmaat
        );
    }

    /** @test */
    public function wedstrijddeelnemerIdIsVerplicht()
    {
        $expectedErrorMessage = "Wedstrijddeelnemer_id is verplicht!";
        $this->getrokkenmaat->wedstrijddeelnemer_id = null;

        $response = $this->wijzigGetrokkenMaat(
            $this->getrokkenmaat
        );

        assertErrorMessage(
            $this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage
        );
    }

    /** @test */
    public function wedstrijddeelnemerIdMoetReedsBestaan()
    {
        $expectedErrorMessage = "Wedstrijddeelnemer_id niet gevonden!";
        $this->getrokkenmaat->wedstrijddeelnemer_id = 666;

        $response = $this->wijzigGetrokkenMaat(
            $this->getrokkenmaat
        );

        assertErrorMessage(
            $this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage
        );
    }

    /** @test */
    public function wedstrijddeelnemerIdMoetVerschillendZijnVanGetrokkenMaatId()
    {
        $expectedErrorMessage = "Wedstrijddeelnemer_id en Getrokken_maat_id moeten verschillend zijn!";
        $this->getrokkenmaat->wedstrijddeelnemer_id = $this->getrokkenmaat->getrokken_maat_id;

        $response = $this->wijzigGetrokkenMaat(
            $this->getrokkenmaat
        );

        assertErrorMessage(
            $this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage
        );
    }

    /** @test */
    public function getrokkenMaatIdIsVerplicht()
    {
        $expectedErrorMessage = "Getrokken_maat_id is verplicht!";
        $this->getrokkenmaat->getrokken_maat_id = null;

        $response = $this->wijzigGetrokkenMaat(
            $this->getrokkenmaat
        );

        assertErrorMessage(
            $this, "getrokken_maat_id", $response, $expectedErrorMessage
        );
    }

    /** @test */
    public function getrokkenMaatIdMoetReedsBestaan()
    {
        $expectedErrorMessage = "Getrokken_maat_id niet gevonden!";
        $this->getrokkenmaat->getrokken_maat_id = 666;

        $response = $this->wijzigGetrokkenMaat(
            $this->getrokkenmaat
        );

        assertErrorMessage(
            $this, "getrokken_maat_id", $response, $expectedErrorMessage
        );
    }

    /** @test */
    public function getrokkenMaatIdMoetVerschillendZijnVanWedstrijddeelnemerId()
    {
        $expectedErrorMessage = "Getrokken_maat_id en Wedstrijddeelnemer_id moeten verschillend zijn!";
        $this->getrokkenmaat->getrokken_maat_id = $this->getrokkenmaat->wedstrijddeelnemer_id;

        $response = $this->wijzigGetrokkenMaat(
            $this->getrokkenmaat
        );

        assertErrorMessage(
            $this, "getrokken_maat_id", $response, $expectedErrorMessage
        );
    }

    /** @test */
    public function combinatieWedstrijddeelnemerIdEnGetrokkenMaatIdIsUniek()
    {
        $expectedErrorMessage = "Getrokken_maat_id bestaat reeds voor wedstrijddeelnemer_id!";
        $getrokkenMaat = bewaarGetrokkenMaat();
        $this->getrokkenmaat->wedstrijddeelnemer_id = $getrokkenMaat->wedstrijddeelnemer_id;
        $this->getrokkenmaat->getrokken_maat_id = $getrokkenMaat->getrokken_maat_id;

        $response = $this->wijzigGetrokkenMaat(
            $this->getrokkenmaat
        );

        assertErrorMessage(
            $this, "getrokken_maat_id", $response, $expectedErrorMessage
        );
    }

    private function wijzigGetrokkenMaat(
        GetrokkenMaat $getrokkenMaat
    ): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'PUT',
                URL_GETROKKENMATEN_ADMIN . $getrokkenMaat->id,
                getrokkenMaatToArray($getrokkenMaat)
            )
        ;
    }
}
