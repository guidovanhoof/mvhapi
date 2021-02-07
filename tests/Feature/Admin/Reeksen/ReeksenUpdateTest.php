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

class ReeksenUpdateTest extends TestCase
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
        $this->reeks = bewaarReeks(["wedstrijd_id" => $this->wedstrijd->id]);
    }

    public function tearDown(): void
    {
        Reeks::query()->delete();
        Wedstrijd::query()->delete();
        Kalender::query()->delete();
        $this->wedstrijd = null;
        $this->reeks = null;

        parent::tearDown();
    }

    /** @test */
    public function reeksNietAanwezig()
    {
        $expectedErrorMessage = "Reeks niet gevonden!";
        $this->reeks->id = 666;

        $response = $this->wijzigReeks($this->reeks);

        $response->assertStatus(404);
        $data = $response->json();
        $this->assertEquals($expectedErrorMessage, $data["message"]);
    }

    /** @test */
    public function reeksWijzigen()
    {
        $wedstrijd = bewaarWedstrijd();
        $this->reeks->wedstrijd_id = $wedstrijd->id;
        $this->reeks->nummer = 66;
        $this->reeks->aanvang = "08:30:00";
        $this->reeks->duur = "02:00:00";
        $this->reeks->gewicht_zak=6969;
        $this->reeks->opmerkigen = "gewijzigde opmerkingen";

        $response = $this->wijzigReeks($this->reeks);

        $response->assertStatus(200);
        $data = $response->json();
        assertReeksEquals($this, $data, $this->reeks);
    }

    /** @test */
    public function wedstrijdIdIsVerplicht() {
        $expectedErrorMessage = "Wedstrijd_id is verplicht!";
        $this->reeks->wedstrijd_id = null;

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "wedstrijd_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijdIdMoetReedsBestaan() {
        $expectedErrorMessage = "Wedstrijd_id niet gevonden!";
        $this->reeks->wedstrijd_id = 666;

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "wedstrijd_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsVerplicht() {
        $expectedErrorMessage = "Nummer is verplicht!";
        $this->reeks->nummer = null;

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsNumeriek() {
        $expectedErrorMessage = "Nummer is niet numeriek!";
        $this->reeks->nummer = "abcd";

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerTussen1En255() {
        $expectedErrorMessage = "Nummer moet liggen tussen 1 en 255!";
        $this->reeks->nummer = 256;

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsUniekPerWedstrijd() {
        $expectedErrorMessage = "Nummer bestaat reeds voor wedstrijd!";
        $reeks = bewaarReeks(["wedstrijd_id" => $this->wedstrijd->id]);
        $reeks->nummer = $this->reeks->nummer;

        $response = $this->wijzigReeks($reeks);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function aanvangIsVerplicht() {
        $expectedErrorMessage = "Aanvang is verplicht!";
        $this->reeks->aanvang = null;

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "aanvang", $response, $expectedErrorMessage);
    }

    /** @test */
    public function aanvangIsGeldigeTijd() {
        $expectedErrorMessage = "Aanvang is geen geldig tijdstip!";
        $this->reeks->aanvang = "abcde";

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "aanvang", $response, $expectedErrorMessage);
    }

    /** @test */
    public function duurIsOptioneel() {
        $this->reeks->duur = null;

        $response = $this->wijzigReeks($this->reeks);

        $response->assertStatus(200);
        $this->assertInDatabase($this->reeks);
    }

    /** @test */
    public function duurIsGeldigeTijd() {
        $expectedErrorMessage = "Duur is geen geldig tijdstip!";
        $this->reeks->duur = "abcde";

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "duur", $response, $expectedErrorMessage);
    }

    /** @test */
    public function gewichtZakIsVerplicht() {
        $expectedErrorMessage = "Gewicht zak is verplicht!";
        $this->reeks->gewicht_zak = null;

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "gewicht_zak", $response, $expectedErrorMessage);
    }

    /** @test */
    public function gewichtZakIsNumeriek() {
        $expectedErrorMessage = "Gewicht zak is niet numeriek!";
        $this->reeks->gewicht_zak = "abcd";

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "gewicht_zak", $response, $expectedErrorMessage);
    }

    /** @test */
    public function gewichtZakGroterOfGelijkAanNul() {
        $expectedErrorMessage = "Gewicht zak moet groter of gelijk aan 0 zijn!";
        $this->reeks->gewicht_zak = -1;

        $response = $this->wijzigReeks($this->reeks);

        assertErrorMessage($this, "gewicht_zak", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $this->reeks->opmerkingen = null;

        $response = $this->wijzigReeks($this->reeks);

        $response->assertStatus(200);
        $this->assertInDatabase($this->reeks);
    }

    /**
     * @param Reeks $reeks
     * @return TestResponse
     */
    private function wijzigReeks(Reeks $reeks): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'PUT',
                    URL_REEKSEN_ADMIN . $reeks->id,
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
