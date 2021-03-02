<?php

namespace Tests\Feature\Admin\Plaatsdeelnemers;

use App\Models\Plaatsdeelnemer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsdeelnemersUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $plaatsdeelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->plaatsdeelnemer = bewaarPlaatsdeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->plaatsdeelnemer = null;

        parent::tearDown();
    }

    /** @test */
    public function plaatsdeelnemerNietAanwezig()
    {
        $this->plaatsdeelnemer->id = 666;

        $response = $this->wijzigPlaatsdeelnemer($this->plaatsdeelnemer);

        assertNietGevonden($this, $response, "Plaatsdeelnemer");
    }

    /** @test */
    public function plaatsdeelnemerWijzigen()
    {
        $plaats = bewaarPlaats();
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $this->plaatsdeelnemer->plaats_id = $plaats->id;
        $this->plaatsdeelnemer->weddstrijddeelnemer_id = $wedstrijddeelnemer->id;
        $this->plaatsdeelnemer->is_weger = 1;

        $response = $this->wijzigPlaatsdeelnemer($this->plaatsdeelnemer);

        $response->assertStatus(200);
        assertPlaatsdeelnemerInDatabase($this, $this->plaatsdeelnemer);
    }

    /** @test */
    public function plaatsIdIsVerplicht() {
        $expectedErrorMessage = "Plaats_id is verplicht!";
        $this->plaatsdeelnemer->plaats_id = null;

        $response = $this->wijzigPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "plaats_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function plaatsIdMoetReedsBestaan() {
        $expectedErrorMessage = "Plaats_id niet gevonden!";
        $this->plaatsdeelnemer->plaats_id = 666;

        $response = $this->wijzigPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "plaats_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijddeelnemerIdIsVerplicht() {
        $expectedErrorMessage = "Wedstrijddeelnemer_id is verplicht!";
        $this->plaatsdeelnemer->wedstrijddeelnemer_id = null;

        $response = $this->wijzigPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijddeelnemerIdMoetReedsBestaan() {
        $expectedErrorMessage = "Wedstrijddeelnemer_id niet gevonden!";
        $this->plaatsdeelnemer->wedstrijddeelnemer_id = 666;

        $response = $this->wijzigPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function deelnemerIsUniekPerPlaats() {
        $expectedErrorMessage = "Deelnemer bestaat reeds voor plaats!";
        $plaatsdeelnemer = bewaarPlaatsdeelnemer();
        $this->plaatsdeelnemer->plaats_id = $plaatsdeelnemer->plaats_id;
        $this->plaatsdeelnemer->wedstrijddeelnemer_id = $plaatsdeelnemer->wedstrijddeelnemer_id;

        $response = $this->wijzigPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wegerIsVerplicht() {
        $expectedErrorMessage = "Weger is verplicht!";
        $this->plaatsdeelnemer->is_weger = null;

        $response = $this->wijzigPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "is_weger", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wegerIsNumeriek() {
        $expectedErrorMessage = 'Weger is niet numeriek!';
        $this->plaatsdeelnemer->is_weger = "abc";

        $response = $this->wijzigPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "is_weger", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wegerTussen0En1() {
        $expectedErrorMessage = 'Weger moet liggen tussen 0 en 1!';
        $this->plaatsdeelnemer->is_weger = 2;

        $response = $this->wijzigPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "is_weger", $response, $expectedErrorMessage);
    }

    /**
     * @param Plaatsdeelnemer $plaatsdeelnemer
     * @return TestResponse
     */
    private function wijzigPlaatsdeelnemer(Plaatsdeelnemer $plaatsdeelnemer): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'PUT',
                    URL_PLAATSDEELNEMERS_ADMIN . $plaatsdeelnemer->id,
                    plaatsdeelnemerToArry($plaatsdeelnemer)
                )
        ;
    }
}
