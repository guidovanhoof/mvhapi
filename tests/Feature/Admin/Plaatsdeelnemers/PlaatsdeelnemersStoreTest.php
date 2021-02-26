<?php

namespace Tests\Feature\Admin\Plaatsdeelnemers;

use App\Models\Plaats;
use App\Models\Plaatsdeelnemer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsdeelnemersStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $plaatsdeelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->plaatsdeelnemer = maakPlaatsdeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->plaatsdeelnemer = null;

        parent::tearDown();
    }

    /** @test */
    public function plaatsdeelnemerAanmaken()
    {
        $response = $this->bewaarPlaatsdeelnemer($this->plaatsdeelnemer);

        $response->assertStatus(201);
        assertPlaatsdeelnemerInDatabase($this, $this->plaatsdeelnemer);
    }

    /** @test */
    public function plaatsIdIsVerplicht() {
        $expectedErrorMessage = "Plaats_id is verplicht!";
        $this->plaatsdeelnemer->plaats_id = null;

        $response = $this->bewaarPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "plaats_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function plaatsIdMoetReedsBestaan() {
        $expectedErrorMessage = "Plaats_id niet gevonden!";
        $this->plaatsdeelnemer->plaats_id = 666;

        $response = $this->bewaarPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "plaats_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijddeelnemerIdIsVerplicht() {
        $expectedErrorMessage = "Wedstrijddeelnemer_id is verplicht!";
        $this->plaatsdeelnemer->wedstrijddeelnemer_id = null;

        $response = $this->bewaarPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijddeelnemerIdMoetReedsBestaan() {
        $expectedErrorMessage = "Wedstrijddeelnemer_id niet gevonden!";
        $this->plaatsdeelnemer->wedstrijddeelnemer_id = 666;

        $response = $this->bewaarPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function deelnemerIsUniekPerPlaats() {
        $expectedErrorMessage = "Deelnemer bestaat reeds voor plaats!";

        $this->bewaarPlaatsdeelnemer($this->plaatsdeelnemer);
        $response = $this->bewaarPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wegerIsVerplicht() {
        $expectedErrorMessage = "Weger is verplicht!";
        $this->plaatsdeelnemer->is_weger = null;

        $response = $this->bewaarPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "is_weger", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wegerIsNumeriek() {
        $expectedErrorMessage = 'Weger is niet numeriek!';
        $this->plaatsdeelnemer->is_weger = "abc";

        $response = $this->bewaarPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "is_weger", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wegerTussen0En1() {
        $expectedErrorMessage = 'Weger moet liggen tussen 0 en 1!';
        $this->plaatsdeelnemer->is_weger = 2;

        $response = $this->bewaarPlaatsdeelnemer($this->plaatsdeelnemer);

        assertErrorMessage($this, "is_weger", $response, $expectedErrorMessage);
    }

    /**
     * @param Plaatsdeelnemer $plaatsdeelnemer
     * @return TestResponse
     */
    private function bewaarPlaatsdeelnemer(Plaatsdeelnemer $plaatsdeelnemer): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'POST',
                    URL_PLAATSDEELNEMERS_ADMIN,
                    plaatsdeelnemerToArry($plaatsdeelnemer)
                )
        ;
    }
}
