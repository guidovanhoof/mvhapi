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

    /** @test */
    public function wedstrijdAanmaken()
    {
        $wedstrijd = $this->maakWedstrijd();

        $response = $this->bewaarWedstrijd($wedstrijd);

        $response->assertStatus(201);
        $this->assertInDatabase($wedstrijd);
    }

    /** @test */
    public function kalenderIdIsVerplicht() {
        $expectedErrorMessage = "Kalender_id is verplicht!";
        $wedstrijd = maakWedstrijd(['kalender_id' => null]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "kalender_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function kalenderIdMoetReedsBestaan() {
        $expectedErrorMessage = "Kalender_id niet gevonden!";
        $wedstrijd = maakWedstrijd(['kalender_id' => 666]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "kalender_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function datumIsVerplicht() {
        $expectedErrorMessage = "Datum is verplicht!";
        $wedstrijd = maakWedstrijd(['datum' => null]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
    }

    /** @test */
    public function datumIsUniek() {
        $expectedErrorMessage = "Datum bestaat reeds!";
        $kalender = bewaarKalender();
        $eersteWedstrijd = bewaarWedstrijd(["kalender_id" => $kalender->id]);
        $wedstrijd = $this->maakWedstrijd(["datum" => $eersteWedstrijd->datum]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
    }

    /** @test */
    public function datumIsGeldigeDatum() {
        $expectedErrorMessage = "Datum is geen geldige datum!";
        $wedstrijd = $this->maakWedstrijd(["datum" => "abcde"]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
    }

    /** @test */
    public function datumMoetInKalenderjaarLiggen() {
        $expectedErrorMessage = "Datum niet in kalenderjaar!";
        $kalender = bewaarKalender(["jaar" => 2020]);
        $wedstrijd = maakWedstrijd(["datum" => "2019-04-28"]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "datum", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsOptioneel() {
        $wedstrijd = $this->maakWedstrijd(["nummer" => null]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        $response->assertStatus(201);
        $this->assertInDatabase($wedstrijd);
    }

    /** @test */
    public function nummerIsNumeriek() {
        $expectedErrorMessage = "Nummer is niet numeriek!";
        $wedstrijd = $this->maakWedstrijd(["nummer" => "abcd"]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerTussen1En65535() {
        $expectedErrorMessage = "Nummer moet liggen tussen 1 en 65535!";
        $wedstrijd = $this->maakWedstrijd(["nummer" => 66666]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function omschrijvingIsVerplicht() {
        $expectedErrorMessage = "Omschrijving is verplicht!";
        $wedstrijd = maakWedstrijd(['omschrijving' => null]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /** @test */
    public function sponsorIsOptioneel() {
        $wedstrijd = $this->maakWedstrijd(["sponsor" => null]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        $response->assertStatus(201);
        $this->assertInDatabase($wedstrijd);
    }

    /** @test */
    public function aanvangIsVerplicht() {
        $expectedErrorMessage = "Aanvang is verplicht!";
        $wedstrijd = maakWedstrijd(['aanvang' => null]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "aanvang", $response, $expectedErrorMessage);
    }

    /** @test */
    public function aanvangIsGeldigeDatum() {
        $expectedErrorMessage = "Aanvang is geen geldig tijdstip!";
        $wedstrijd = $this->maakWedstrijd(["aanvang" => "abcde"]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "aanvang", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijdtypeIdIsVerplicht() {
        $expectedErrorMessage = "Wedstrijdtype_id is verplicht!";
        $wedstrijd = maakWedstrijd(['wedstrijdtype_id' => null]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "wedstrijdtype_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijdtypeIdMoetReedsBestaan() {
        $expectedErrorMessage = "Wedstrijdtype_id niet gevonden!";
        $wedstrijd = maakWedstrijd(['wedstrijdtype_id' => 666]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        assertErrorMessage($this, "wedstrijdtype_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $wedstrijd = $this->maakWedstrijd(["opmerkingen" => null]);

        $response = $this->bewaarWedstrijd($wedstrijd);

        $response->assertStatus(201);
        $this->assertInDatabase($wedstrijd);
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
                $wedstrijd->toArray()
            )
            ->assertJson($wedstrijd->toJson());
    }
}
