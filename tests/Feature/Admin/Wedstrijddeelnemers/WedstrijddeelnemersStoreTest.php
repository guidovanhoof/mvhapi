<?php

namespace Tests\Feature\Admin\Wedstrijddeelnemers;

use App\Models\Wedstrijddeelnemer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $wedstrijddeelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijddeelnemer = maakWedstrijddeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->wedstrijddeelnemer = null;

        parent::tearDown();
    }

    /** @test */
    public function wedstrijddeelnemerAanmaken()
    {
        $response = $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);

        $response->assertStatus(201);
        assertWedstrijddeelnemerInDatabase($this, $this->wedstrijddeelnemer);
    }

    /** @test */
    public function wedstrijdIdIsVerplicht() {
        $expectedErrorMessage = "Wedstrijd_id is verplicht!";
        $this->wedstrijddeelnemer->wedstrijd_id = null;

        $response = $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "wedstrijd_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijdIdMoetReedsBestaan() {
        $expectedErrorMessage = "Wedstrijd_id niet gevonden!";
        $this->wedstrijddeelnemer->wedstrijd_id = 666;

        $response = $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "wedstrijd_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function deelnemerIdIsVerplicht() {
        $expectedErrorMessage = "Deelnemer_id is verplicht!";
        $this->wedstrijddeelnemer->deelnemer_id = null;

        $response = $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "deelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function deelnemerIdMoetReedsBestaan() {
        $expectedErrorMessage = "Deelnemer_id niet gevonden!";
        $this->wedstrijddeelnemer->deelnemer_id = 666;

        $response = $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "deelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function deelnemerIsUniekPerWedstrijd() {
        $expectedErrorMessage = "Deelnemer bestaat reeds voor wedstrijd!";

        $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);
        $response = $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "deelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function diskwalificatieIsVerplicht() {
        $expectedErrorMessage = "Diskwalificatie is verplicht!";
        $this->wedstrijddeelnemer->is_gediskwalificeerd = null;

        $response = $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "is_gediskwalificeerd", $response, $expectedErrorMessage);
    }

    /** @test */
    public function diskwalificatieIsNumeriek() {
        $expectedErrorMessage = 'Diskwalificatie is niet numeriek!';
        $this->wedstrijddeelnemer->is_gediskwalificeerd = "abc";

        $response = $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "is_gediskwalificeerd", $response, $expectedErrorMessage);
    }

    /** @test */
    public function diskwalificatieTussen0En1() {
        $expectedErrorMessage = 'Diskwalificatie moet liggen tussen 0 en 1!';
        $this->wedstrijddeelnemer->is_gediskwalificeerd = 2;

        $response = $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "is_gediskwalificeerd", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $this->wedstrijddeelnemer->opmerkingen = null;

        $response = $this->bewaarWedstrijddeelnemer($this->wedstrijddeelnemer);

        $response->assertStatus(201);
        assertWedstrijddeelnemerInDatabase($this, $this->wedstrijddeelnemer);
    }

    /**
     * @param Wedstrijddeelnemer $wedstrijddeelnemer
     * @return TestResponse
     */
    private function bewaarWedstrijddeelnemer(Wedstrijddeelnemer $wedstrijddeelnemer): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'POST',
                    URL_WEDSTRIJDDEELNEMERS_ADMIN,
                    wedstrijddeelnemerToArry($wedstrijddeelnemer)
                )
        ;
    }
}
