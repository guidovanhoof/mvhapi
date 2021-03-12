<?php

namespace Tests\Feature\Admin\WedstrijddeelnemerJeugdcategorieen;

use App\Models\WedstrijddeelnemerJeugdcategorie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersJeugdcategorieenStoreTest extends TestCase
{
    use RefreshDatabase;

    private $wedstrijddeelnemerJeugdcategorie;

    public function  setUp(): void
    {
        parent::setUp();

        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();
        $jeugdcategorie = bewaarJeugdcategorie();
        $this->wedstrijddeelnemerJeugdcategorie = maakWedstrijddeelnemerJeugdcategorie(
            [
                'wedstrijddeelnemer_id' => $wedstrijddeelnemer->id,
                'jeugdcategorie_id' => $jeugdcategorie->id,
            ]
        );
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function wedstrijddeelnemerJeugdcategorieBewaren()
    {
        $response = $this->bewaarWedstrijddeelnemerJeugdcategorie(
            $this->wedstrijddeelnemerJeugdcategorie
        );

        $response->assertStatus(201);
        assertWedstrijddeelnemerJeugdcategorieInDatabase(
            $this, $this->wedstrijddeelnemerJeugdcategorie
        );
    }

    /** @test */
    public function wedstrijddeelnemerIdIsVerplicht()
    {
        $expectedErrorMessage = "Wedstrijddeelnemer_id is verplicht!";
        $this->wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id = null;

        $response = $this->bewaarWedstrijddeelnemerJeugdcategorie(
            $this->wedstrijddeelnemerJeugdcategorie
        );

        assertErrorMessage(
            $this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage
        );
    }

    /** @test */
    public function wedstrijddeelnemerIdMoetReedsBestaan()
    {
        $expectedErrorMessage = "Wedstrijddeelnemer_id niet gevonden!";
        $this->wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id = 666;

        $response = $this->bewaarWedstrijddeelnemerJeugdcategorie(
            $this->wedstrijddeelnemerJeugdcategorie
        );

        assertErrorMessage(
            $this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage
        );
    }

    /** @test */
    public function wedstrijddeelnemerIdIsUniek()
    {
        $expectedErrorMessage = "Wedstrijddeelnemer_id bestaat reeds!";
        $wedstrijddeelnemerJeugdcategorie = bewaarWedstrijddeelnemerJeugdcategorie();
        $this->wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id =
            $wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id;

        $response = $this->bewaarWedstrijddeelnemerJeugdcategorie(
            $this->wedstrijddeelnemerJeugdcategorie
        );

        assertErrorMessage(
            $this, "wedstrijddeelnemer_id", $response, $expectedErrorMessage
        );
    }

    /** @test */
    public function jeugdcategorieIdIsVerplicht()
    {
        $expectedErrorMessage = "Jeugdcategorie_id is verplicht!";
        $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id = null;

        $response = $this->bewaarWedstrijddeelnemerJeugdcategorie(
            $this->wedstrijddeelnemerJeugdcategorie
        );

        assertErrorMessage(
            $this, "jeugdcategorie_id", $response, $expectedErrorMessage
        );
    }

    /** @test */
    public function jeugdcategorieIdMoetReedsBestaan()
    {
        $expectedErrorMessage = "Jeugdcategorie_id niet gevonden!";
        $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id = 666;

        $response = $this->bewaarWedstrijddeelnemerJeugdcategorie(
            $this->wedstrijddeelnemerJeugdcategorie
        );

        assertErrorMessage(
            $this, "jeugdcategorie_id", $response, $expectedErrorMessage
        );
    }

    /**
     * @param $wedstrijddeelnemerJeugdcategorie
     * @return TestResponse
     */
    private function bewaarWedstrijddeelnemerJeugdcategorie(
        WedstrijddeelnemerJeugdcategorie $wedstrijddeelnemerJeugdcategorie
    ): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'POST',
                URL_WEDSTRIJDDEELNEMERJEUGDCATEGORIEEN_ADMIN,
                wedstrijddeelnemerJeugdcategorieToArrayw($wedstrijddeelnemerJeugdcategorie)
            )
        ;
    }
}
