<?php

namespace Tests\Feature\Admin\Wedstrijden;

use App\Models\Wedstrijd;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdenStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $kalender;
    private $wedstrijd;

    public function setUp(): void
    {
        parent::setUp();

        $this->kalender = bewaarKalender();
        $this->wedstrijd = $this->maakWedstrijd();
    }

    public function tearDown(): void
    {
        cleanUpDb("wedstrijden");
        $this->wedstrijd = null;
        $this->kalender = null;

        parent::tearDown();
    }

    /** @test */
    public function wedstrijdAanmaken()
    {
        $response = $this->bewaarWedstrijd($this->wedstrijd);

        $response->assertStatus(201);
        $this->assertInDatabase($this->wedstrijd);
    }

    /** @test */
    public function kalenderIdIsVerplicht() {
        $expectedErrorMessage = "Kalender_id is verplicht!";
        $this->wedstrijd->kalender_id = null;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "kalender_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function kalenderIdMoetReedsBestaan() {
        $expectedErrorMessage = "Kalender_id niet gevonden!";
        $this->wedstrijd->kalender_id = 666;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "kalender_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function datumIsVerplicht() {
        $expectedErrorMessage = "Datum is verplicht!";
        $this->wedstrijd->datum = null;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
    }

    /** @test */
    public function datumIsUniek() {
        $expectedErrorMessage = "Datum bestaat reeds!";
        $eersteWedstrijd = bewaarWedstrijd(["kalender_id" => $this->kalender->id]);
        $wedstrijd = $this->maakWedstrijd(["datum" => $eersteWedstrijd->datum]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
        $eersteWedstrijd->delete();
    }

    /** @test */
    public function datumIsGeldigeDatum() {
        $expectedErrorMessage = "Datum is geen geldige datum!";
        $this->wedstrijd->datum = "abcde";

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
    }

    /** @test */
    public function datumMoetInKalenderjaarLiggen() {
        $expectedErrorMessage = "Datum niet in kalenderjaar!";
        $this->wedstrijd->datum = ($this->kalender->jaar - 1) .  "-04-28";

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsOptioneel() {
        $this->wedstrijd->nummer = null;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        $response->assertStatus(201);
        $this->assertInDatabase($this->wedstrijd);
    }

    /** @test */
    public function nummerIsNumeriek() {
        $expectedErrorMessage = "Nummer is niet numeriek!";
        $this->wedstrijd->nummer = "abcd";

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerTussen1En65535() {
        $expectedErrorMessage = "Nummer moet liggen tussen 1 en 65535!";
        $this->wedstrijd->nummer = 66666;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function omschrijvingIsVerplicht() {
        $expectedErrorMessage = "Omschrijving is verplicht!";
        $this->wedstrijd->omschrijving = null;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /** @test */
    public function sponsorIsOptioneel() {
        $this->wedstrijd->sponsor = null;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        $response->assertStatus(201);
        $this->assertInDatabase($this->wedstrijd);
    }

    /** @test */
    public function aanvangIsVerplicht() {
        $expectedErrorMessage = "Aanvang is verplicht!";
        $this->wedstrijd->aanvang = null;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "aanvang", $response, $expectedErrorMessage);
    }

    /** @test */
    public function aanvangIsGeldigeTijd() {
        $expectedErrorMessage = "Aanvang is geen geldig tijdstip!";
        $this->wedstrijd->aanvang = "abcde";

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "aanvang", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijdtypeIdIsVerplicht() {
        $expectedErrorMessage = "Wedstrijdtype_id is verplicht!";
        $this->wedstrijd->wedstrijdtype_id = null;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "wedstrijdtype_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijdtypeIdMoetReedsBestaan() {
        $expectedErrorMessage = "Wedstrijdtype_id niet gevonden!";
        $this->wedstrijd->wedstrijdtype_id = 666;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        assertErrorMessage($this, "wedstrijdtype_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $this->wedstrijd->opmerkingen = null;

        $response = $this->bewaarWedstrijd($this->wedstrijd);

        $response->assertStatus(201);
        $this->assertInDatabase($this->wedstrijd);
    }

    /**
     * @param Wedstrijd $wedstrijd
     * @return TestResponse
     */
    private function bewaarWedstrijd(Wedstrijd $wedstrijd): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'POST',
                    URL_WEDSTRIJDEN_ADMIN,
                    wedstrijdToArray($wedstrijd)
                )
        ;
    }

    /**
     * @param array $velden
     * @return Collection|Model|mixed
     */
    private function maakWedstrijd($velden = [])
    {
        if (!isset($velden["datum"])) {
            $faker = Factory::create();
            $datum = $faker->dateTimeBetween($this->kalender->jaar . '-01-01', $this->kalender->jaar . '-12-31');
            $velden["datum"] = $datum->format('Y-m-d');
        }
        return
            maakWedstrijd(
                array_merge(["kalender_id" => $this->kalender->id], $velden)
            );
    }

/**
 * @param Wedstrijd $wedstrijd
 */
    private function assertInDatabase(Wedstrijd $wedstrijd): void
    {
        $this
            ->assertDatabaseHas(
                'wedstrijden',
                wedstrijdToArray($wedstrijd)
            )
            ->assertJson($wedstrijd->toJson());
    }
}
