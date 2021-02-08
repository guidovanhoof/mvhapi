<?php

namespace Tests\Feature\Admin\Reeksen;

use App\Models\Kalender;
use App\Models\Reeks;
use App\Models\Wedstrijd;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ReeksenStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $wedstrijd;
    private $reeks;

    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijd = bewaarWedstrijd();
        $this->reeks = maakReeks(["wedstrijd_id" => $this->wedstrijd->id]);
    }

    public function tearDown(): void
    {
        cleanUpDb("reeksen");
        $this->wedstrijd = null;
        $this->reeks = null;

        parent::tearDown();
    }

    /** @test */
    public function reeksAanmaken()
    {
        $response = $this->bewaarReeks($this->reeks);

        $response->assertStatus(201);
        $this->assertInDatabase($this->reeks);
    }

    /** @test */
    public function wedstrijdIdIsVerplicht() {
        $expectedErrorMessage = "Wedstrijd_id is verplicht!";
        $this->reeks->wedstrijd_id = null;

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "wedstrijd_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijdIdMoetReedsBestaan() {
        $expectedErrorMessage = "Wedstrijd_id niet gevonden!";
        $this->reeks->wedstrijd_id = 666;

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "wedstrijd_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsVerplicht() {
        $expectedErrorMessage = "Nummer is verplicht!";
        $this->reeks->nummer = null;

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsNumeriek() {
        $expectedErrorMessage = "Nummer is niet numeriek!";
        $this->reeks->nummer = "abcd";

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerTussen1En255() {
        $expectedErrorMessage = "Nummer moet liggen tussen 1 en 255!";
        $this->reeks->nummer = 256;

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsUniekPerWedstrijd() {
        $expectedErrorMessage = "Nummer bestaat reeds voor wedstrijd!";
        $reeks = bewaarReeks(
            ["wedstrijd_id" => $this->wedstrijd->id, "nummer" => $this->reeks->nummer]
        );

        $this->bewaarReeks($this->reeks);
        $response = $this->bewaarReeks($reeks);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function aanvangIsVerplicht() {
        $expectedErrorMessage = "Aanvang is verplicht!";
        $this->reeks->aanvang = null;

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "aanvang", $response, $expectedErrorMessage);
    }

    /** @test */
    public function aanvangIsGeldigeTijd() {
        $expectedErrorMessage = "Aanvang is geen geldig tijdstip!";
        $this->reeks->aanvang = "abcde";

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "aanvang", $response, $expectedErrorMessage);
    }

    /** @test */
    public function duurIsOptioneel() {
        $this->reeks->duur = null;

        $response = $this->bewaarReeks($this->reeks);

        $this->assertInDatabase($this->reeks);
    }

    /** @test */
    public function duurIsGeldigeTijd() {
        $expectedErrorMessage = "Duur is geen geldig tijdstip!";
        $this->reeks->duur = "abcde";

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "duur", $response, $expectedErrorMessage);
    }

    /** @test */
    public function gewichtZakIsVerplicht() {
        $expectedErrorMessage = "Gewicht zak is verplicht!";
        $this->reeks->gewicht_zak = null;

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "gewicht_zak", $response, $expectedErrorMessage);
    }

    /** @test */
    public function gewichtZakIsNumeriek() {
        $expectedErrorMessage = "Gewicht zak is niet numeriek!";
        $this->reeks->gewicht_zak = "abcd";

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "gewicht_zak", $response, $expectedErrorMessage);
    }

    /** @test */
    public function gewichtZakGroterOfGelijkAanNul() {
        $expectedErrorMessage = "Gewicht zak moet groter of gelijk aan 0 zijn!";
        $this->reeks->gewicht_zak = -1;

        $response = $this->bewaarReeks($this->reeks);

        assertErrorMessage($this, "gewicht_zak", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $this->reeks->opmerkingen = null;

        $response = $this->bewaarReeks($this->reeks);

        $response->assertStatus(201);
        $this->assertInDatabase($this->reeks);
    }

    /**
     * @param Reeks $reeks
     * @return TestResponse
     */
    private function bewaarReeks(Reeks $reeks): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'POST',
                    URL_REEKSEN_ADMIN,
                    reeksToArray($reeks)
                )
        ;
    }

/**
 * @param Reeks $reeks
 */
    private function assertInDatabase(Reeks $reeks): void
    {
        $this
            ->assertDatabaseHas(
                'reeksen',
                reeksToArray($reeks)
            )
            ->assertJson($reeks->toJson())
        ;
    }
}
