<?php

namespace Tests\Feature\Admin\Wedstrijden;

use App\Models\Wedstrijd;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdenUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $kalender = null;
    protected $wedstrijd = null;
    protected $datum = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->kalender = bewaarKalender(["jaar" => date("Y")]);
        $this->datum = $this->kalender->jaar . "-04-28";
        $this->wedstrijd = bewaarWedstrijd(
            [
                "kalender_id" => $this->kalender->id,
                "datum" => $this->datum,
            ])
        ;
    }

    public function tearDown(): void
    {
        $this->wedstrijd->delete();
        $this->wedstrijd = null;
        $this->kalender->delete();
        $this->kalender = null;

        parent::tearDown();
    }

    /** @test */
    public function wedstrijdNietAanwezig()
    {
        $expectedErrorMessage = "Wedstrijd niet gevonden!";

        $response = $this->updateWedstrijd($this->wedstrijd, "1900-04-28");

        $response->assertStatus(404);
        $data = $response->json();
        $this->assertEquals($expectedErrorMessage, $data["message"]);
    }

    /** @test */
    public function kalenderIdWijzigen()
    {
        $nieuweKalender = bewaarKalender();
        $this->wedstrijd->kalender_id = $nieuweKalender->id;
        $this->wedstrijd->datum = $nieuweKalender->jaar . substr($this->wedstrijd->datum, 4, 6);

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        $response->assertStatus(200);
        $this->assertInDatabase($this->wedstrijd);
    }

    /** @test */
    public function kalenderIdIsVerplicht() {
        $expectedErrorMessage = "Kalender_id is verplicht!";
        $this->wedstrijd->kalender_id = null;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        assertErrorMessage($this, "kalender_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function kalenderIdMoetReedsBestaan() {
        $expectedErrorMessage = "Kalender_id niet gevonden!";
        $this->wedstrijd->kalender_id = 666;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        assertErrorMessage($this, "kalender_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function datumIsVerplicht() {
        $expectedErrorMessage = "Datum is verplicht!";
        $this->wedstrijd->datum = null;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
    }

    /** @test */
    public function datumIsUniek() {
        $expectedErrorMessage = "Datum bestaat reeds!";
        $wedstrijd = bewaarWedstrijd(
          [
              "kalender_id" => $this->kalender->id,
              "datum" => $this->kalender->jaar . "-05-01",
          ]
        );
        $this->wedstrijd->datum = $wedstrijd->datum;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
        $wedstrijd->delete();
    }

    /** @test */
    public function datumIsGeldigeDatum() {
        $expectedErrorMessage = "Datum is geen geldige datum!";
        $wedstrijd = bewaarWedstrijd();
        $datum = $wedstrijd->datum;
        $wedstrijd->datum = "abcde";

        $response = $this->updateWedstrijd($wedstrijd, $datum);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
    }

    /** @test */
    public function datumMoetInKalenderjaarLiggen() {
        $expectedErrorMessage = "Datum niet in kalenderjaar!";
        $kalender = bewaarKalender(["jaar" => 2020]);
        $wedstrijd =bewaarWedstrijd(["kalender_id" => $kalender->id, "datum" => "2020-04-28"]);
        $datum = $wedstrijd->datum;
        $wedstrijd->datum = "2019-04-28";

        $response = $this->updateWedstrijd($wedstrijd, $datum);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsOptioneel() {
        $this->wedstrijd->nummer = null;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        $response->assertStatus(200);
        $this->assertInDatabase($this->wedstrijd);
    }

    /** @test */
    public function nummerIsNumeriek() {
        $expectedErrorMessage = "Nummer is niet numeriek!";
        $this->wedstrijd->nummer = "abcd";

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerTussen1En65535() {
        $expectedErrorMessage = "Nummer moet liggen tussen 1 en 65535!";
        $this->wedstrijd->nummer = 66666;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function omschrijvingIsVerplicht() {
        $expectedErrorMessage = "Omschrijving is verplicht!";
        $this->wedstrijd->omschrijving = null;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /** @test */
    public function sponsorIsOptioneel() {
        $this->wedstrijd->sponsor = null;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        $response->assertStatus(200);
        $this->assertInDatabase($this->wedstrijd);
    }

    /** @test */
    public function aanvangIsVerplicht() {
        $expectedErrorMessage = "Aanvang is verplicht!";
        $this->wedstrijd->aanvang = null;

        $response = $this->updateWedstrijd($this->wedstrijd,$this->datum);

        assertErrorMessage($this, "aanvang", $response, $expectedErrorMessage);
    }

    /** @test */
    public function aanvangIsGeldigeDatum() {
        $expectedErrorMessage = "Aanvang is geen geldig tijdstip!";
        $this->wedstrijd->aanvang = "abcde";

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        assertErrorMessage($this, "aanvang", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijdtypeIdIsVerplicht() {
        $expectedErrorMessage = "Wedstrijdtype_id is verplicht!";
        $this->wedstrijd->wedstrijdtype_id = null;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        assertErrorMessage($this, "wedstrijdtype_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijdtypeIdMoetReedsBestaan() {
        $expectedErrorMessage = "Wedstrijdtype_id niet gevonden!";
        $this->wedstrijd->wedstrijdtype_id = 666;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        assertErrorMessage($this, "wedstrijdtype_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $this->wedstrijd->opmerkingen = null;

        $response = $this->updateWedstrijd($this->wedstrijd, $this->datum);

        $response->assertStatus(200);
        $this->assertInDatabase($this->wedstrijd);
    }

    /**
     * @param Wedstrijd $wedstrijd
     * @param $datum
     * @return TestResponse
     */
    private function updateWedstrijd(Wedstrijd $wedstrijd, $datum): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'PUT',
                    URL_WEDSTRIJDEN_ADMIN . $datum,
                    [
                        'kalender_id' => $wedstrijd->kalender_id,
                        'datum' => $wedstrijd->datum,
                        'nummer' => $wedstrijd->nummer,
                        'omschrijving' => $wedstrijd->omschrijving,
                        'sponsor' => $wedstrijd->sponsor,
                        'aanvang' => $wedstrijd->aanvang,
                        'wedstrijdtype_id' => $wedstrijd->wedstrijdtype_id,
                        'opmerkingen' => $wedstrijd->opmerkingen,
                    ]
                )
        ;
    }

    /**
     * @param array $velden
     * @return Collection|Model|mixed
     */
    private function maakWedstrijd($velden = [])
    {
        $kalender = bewaarKalender();
        if (!isset($velden["datum"])) {
            $faker = Factory::create();
            $datum = $faker->dateTimeBetween($kalender->jaar . '-01-01', $kalender->jaar . '-12-31');
            $velden["datum"] = $datum->format('Y-m-d');
        }
        return
            maakWedstrijd(
                array_merge(["kalender_id" => $kalender->id], $velden)
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
