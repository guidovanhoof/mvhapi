<?php

namespace Tests\Feature\Admin\Gewichten;

use App\Models\Gewicht;
use App\Models\Plaats;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GewichtenUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $plaats;
    private $gewicht;

    public function setUp(): void
    {
        parent::setUp();

        $this->plaats = bewaarPlaats();
        $this->gewicht = bewaarGewicht(["plaats_id" => $this->plaats->id]);
    }

    public function tearDown(): void
    {
        cleanUpDb("gewichten");
        $this->plaats = null;
        $this->gewicht = null;

        parent::tearDown();
    }

    /** @test */
    public function gewichtNietAanwezig()
    {
        $expectedErrorMessage = "Gewicht niet gevonden!";
        $this->gewicht->id = 666;

        $response = $this->wijzigGewicht($this->gewicht);

        $response->assertStatus(404);
        $errorMessage = $response->json()["message"];
        $this->assertEquals($expectedErrorMessage, $errorMessage);
    }

    /** @test */
    public function gewichtWijzigen()
    {
        $plaats = bewaarPlaats();
        $this->gewicht->plaats_id = $plaats->id;
        $this->gewicht->gewicht = 666;
        $this->gewicht->is_gelidg = 0;

        $response = $this->wijzigGewicht($this->gewicht);

        $response->assertStatus(200);
        $this->assertInDatabase($this->gewicht);
    }

    /** @test */
    public function plaatsIdIsVerplicht() {
        $expectedErrorMessage = "Plaats_id is verplicht!";
        $this->gewicht->plaats_id = null;

        $response = $this->wijzigGewicht($this->gewicht);

        assertErrorMessage($this, "plaats_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function plaatsIdMoetReedsBestaan() {
        $expectedErrorMessage = "Plaats_id niet gevonden!";
        $this->gewicht->plaats_id = 666;

        $response = $this->wijzigGewicht($this->gewicht);

        assertErrorMessage($this, "plaats_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function gewichtIsVerplicht() {
        $expectedErrorMessage = "Gewicht is verplicht!";
        $this->gewicht->gewicht = null;

        $response = $this->wijzigGewicht($this->gewicht);

        assertErrorMessage($this, "gewicht", $response, $expectedErrorMessage);
    }

    /** @test */
    public function gewichtIsNumeriek() {
        $expectedErrorMessage = "Gewicht is niet numeriek!";
        $this->gewicht->gewicht = "abcd";

        $response = $this->wijzigGewicht($this->gewicht);

        assertErrorMessage($this, "gewicht", $response, $expectedErrorMessage);
    }

    /** @test */
    public function GewichtGroterDanNul() {
        $expectedErrorMessage = "Gewicht moet groter dan 0 zijn!";
        $this->gewicht->gewicht = 0;

        $response = $this->wijzigGewicht($this->gewicht);

        assertErrorMessage($this, "gewicht", $response, $expectedErrorMessage);
    }

    /** @test */
    public function geldigheidIsVerplicht() {
        $expectedErrorMessage = "Geldigheid is verplicht!";
        $this->gewicht->is_geldig = null;

        $response = $this->wijzigGewicht($this->gewicht);

        assertErrorMessage($this, "is_geldig", $response, $expectedErrorMessage);
    }

    /** @test */
    public function geldigheidIsNumeriek() {
        $expectedErrorMessage = 'Geldigheid is niet numeriek!';
        $this->gewicht->is_geldig = "abc";

        $response = $this->wijzigGewicht($this->gewicht);

        assertErrorMessage($this, "is_geldig", $response, $expectedErrorMessage);
    }

    /** @test */
    public function geldigheidTussen0En1() {
        $expectedErrorMessage = 'Geldigheid moet liggen tussen 0 en 1!';
        $this->gewicht->is_geldig = 2;

        $response = $this->wijzigGewicht($this->gewicht);

        assertErrorMessage($this, "is_geldig", $response, $expectedErrorMessage);
    }

    /**
     * @param Gewicht $gewicht
     * @return TestResponse
     */
    private function wijzigGewicht(Gewicht $gewicht): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'PUT',
                    URL_GEWICHTEN_ADMIN . $gewicht->id,
                    gewichtToArry($gewicht)
                )
        ;
    }

    /**
     * @param Gewicht $gewicht
     */
    private function assertInDatabase(Gewicht $gewicht): void
    {
        $this
            ->assertDatabaseHas(
                'gewichten',
                gewichtToArry($gewicht)
            )
            ->assertJson($gewicht->toJson())
        ;
    }
}
