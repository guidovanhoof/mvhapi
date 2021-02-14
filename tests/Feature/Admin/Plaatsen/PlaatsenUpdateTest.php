<?php

namespace Tests\Feature\Admin\Plaatsen;

use App\Models\Plaats;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsenUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $reeks;
    private $plaats;

    public function setUp(): void
    {
        parent::setUp();

        $this->reeks = bewaarReeks();
        $this->plaats = bewaarPlaats(["reeks_id" => $this->reeks->id]);
    }

    public function tearDown(): void
    {
        cleanUpDb("plaatsen");
        $this->reeks = null;
        $this->plaats = null;

        parent::tearDown();
    }

    /** @test */
    public function plaatsNietAanwezig()
    {
        $expectedErrorMessage = "Plaats niet gevonden!";
        $this->plaats->id = 666;

        $response = $this->wijzigPlaats($this->plaats);

        $response->assertStatus(404);
        $errorMessage = $response->json()["message"];
        $this->assertEquals($expectedErrorMessage, $errorMessage);
    }

    /** @test */
    public function plaatsWijzigen()
    {
        $reeks = bewaarReeks();
        $this->plaats->reeks_id = $reeks->id;
        $this->plaats->nummer = 66;
        $this->plaats->opmerkingen = "gewijzigd!";

        $response = $this->wijzigPlaats($this->plaats);

        $response->assertStatus(200);
        $this->assertInDatabase($this->plaats);
    }

    /** @test */
    public function reeksIdIsVerplicht() {
        $expectedErrorMessage = "Reeks_id is verplicht!";
        $this->plaats->reeks_id = null;

        $response = $this->wijzigPlaats($this->plaats);

        assertErrorMessage($this, "reeks_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function reeksIdMoetReedsBestaan() {
        $expectedErrorMessage = "Reeks_id niet gevonden!";
        $this->plaats->reeks_id = 666;

        $response = $this->wijzigPlaats($this->plaats);

        assertErrorMessage($this, "reeks_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsVerplicht() {
        $expectedErrorMessage = "Nummer is verplicht!";
        $this->plaats->nummer = null;

        $response = $this->wijzigPlaats($this->plaats);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsNumeriek() {
        $expectedErrorMessage = "Nummer is niet numeriek!";
        $this->plaats->nummer = "abcd";

        $response = $this->wijzigPlaats($this->plaats);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerTussen1En255() {
        $expectedErrorMessage = "Nummer moet liggen tussen 1 en 255!";
        $this->plaats->nummer = 256;

        $response = $this->wijzigPlaats($this->plaats);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsUniekPerReeks() {
        $expectedErrorMessage = "Nummer bestaat reeds voor reeks!";
        $plaats = bewaarPlaats(
            ["reeks_id" => $this->reeks->id, "nummer" => 66]
        );
        $this->plaats->nummer = $plaats->nummer;

        $response = $this->wijzigPlaats($this->plaats);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $this->plaats->opmerkingen = null;

        $response = $this->wijzigPlaats($this->plaats);

        $response->assertStatus(200);
        $this->assertInDatabase($this->plaats);
    }

    /**
     * @param Plaats $plaats
     */
    private function assertInDatabase(Plaats $plaats): void
    {
        $this
            ->assertDatabaseHas(
                'plaatsen',
                plaatsToArray($plaats)
            )
            ->assertJson($plaats->toJson())
        ;
    }

    /**
     * @param Plaats $plaats
     * @return TestResponse
     */
    private function wijzigPlaats(Plaats $plaats): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'PUT',
                    URL_PLAATSEN_ADMIN . $plaats->id,
                    plaatsToArray($plaats)
                )
            ;
    }
}
