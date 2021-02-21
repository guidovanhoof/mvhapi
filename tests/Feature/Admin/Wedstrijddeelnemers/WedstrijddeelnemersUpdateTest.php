<?php

namespace Tests\Feature\Admin\Wedstrijddeelnemers;

use App\Models\Wedstrijddeelnemer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $wedstrijddeelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijddeelnemer = bewaarWedstrijddeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb("wedstrijddeelnemers");
        $this->wedstrijddeelnemer = null;

        parent::tearDown();
    }

    /** @test */
    public function wedstrijddeelnemerWijzigen()
    {
        $wedstrijd = bewaarWedstrijd();
        $deelnemer = bewaarDeelnemer();
        $this->wedstrijddeelnemer->wedstrijd_id = $wedstrijd->id;
        $this->wedstrijddeelnemer->deelnemer_id = $deelnemer->id;
        $this->wedstrijddeelnemer->is_gediskwalificeerd = 1;
        $this->wedstrijddeelnemer->opmerkingen = "gewijzigd";

        $response = $this->wijzigWedstrijddeelnemer($this->wedstrijddeelnemer);

        $response->assertStatus(200);
        assertWedstrijddeelnemerInDatabase($this, $this->wedstrijddeelnemer);
    }

    /** @test */
    public function wedstrijdIdIsVerplicht() {
        $expectedErrorMessage = "Wedstrijd_id is verplicht!";
        $this->wedstrijddeelnemer->wedstrijd_id = null;

        $response = $this->wijzigWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "wedstrijd_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function wedstrijdIdMoetReedsBestaan() {
        $expectedErrorMessage = "Wedstrijd_id niet gevonden!";
        $this->wedstrijddeelnemer->wedstrijd_id = 666;

        $response = $this->wijzigWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "wedstrijd_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function deelnemerIdIsVerplicht() {
        $expectedErrorMessage = "Deelnemer_id is verplicht!";
        $this->wedstrijddeelnemer->deelnemer_id = null;

        $response = $this->wijzigWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "deelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function deelnemerIdMoetReedsBestaan() {
        $expectedErrorMessage = "Deelnemer_id niet gevonden!";
        $this->wedstrijddeelnemer->deelnemer_id = 666;

        $response = $this->wijzigWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "deelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function deelnemerIsUniekPerWedstrijd() {
        $expectedErrorMessage = "Deelnemer bestaat reeds voor wedstrijd!";
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $this->wedstrijddeelnemer->wedstrijd_id = $wedstrijddeelnemer->wedstrijd_id;
        $this->wedstrijddeelnemer->deelnemer_id = $wedstrijddeelnemer->deelnemer_id;

        $response = $this->wijzigWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "deelnemer_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function diskwalificatieIsVerplicht() {
        $expectedErrorMessage = "Diskwalificatie is verplicht!";
        $this->wedstrijddeelnemer->is_gediskwalificeerd = null;

        $response = $this->wijzigWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "is_gediskwalificeerd", $response, $expectedErrorMessage);
    }

    /** @test */
    public function diskwalificatieIsNumeriek() {
        $expectedErrorMessage = 'Diskwalificatie is niet numeriek!';
        $this->wedstrijddeelnemer->is_gediskwalificeerd = "abc";

        $response = $this->wijzigWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "is_gediskwalificeerd", $response, $expectedErrorMessage);
    }

    /** @test */
    public function diskwalificatieTussen0En1() {
        $expectedErrorMessage = 'Diskwalificatie moet liggen tussen 0 en 1!';
        $this->wedstrijddeelnemer->is_gediskwalificeerd = 2;

        $response = $this->wijzigWedstrijddeelnemer($this->wedstrijddeelnemer);

        assertErrorMessage($this, "is_gediskwalificeerd", $response, $expectedErrorMessage);
    }

    /** @test */
    public function opmerkingenIsOptioneel() {
        $this->wedstrijddeelnemer->opmerkingen = null;

        $response = $this->wijzigWedstrijddeelnemer($this->wedstrijddeelnemer);

        $response->assertStatus(200);
        assertWedstrijddeelnemerInDatabase($this, $this->wedstrijddeelnemer);
    }

    /**
     * @param Wedstrijddeelnemer $wedstrijddeelnemer
     * @return TestResponse
     */
    private function wijzigWedstrijddeelnemer(Wedstrijddeelnemer $wedstrijddeelnemer): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'PUT',
                    URL_WEDSTRIJDDEELNEMERS_ADMIN . $wedstrijddeelnemer->id,
                    wedstrijddeelnemerToArry($wedstrijddeelnemer)
                )
        ;
    }
}
