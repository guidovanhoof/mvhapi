<?php

namespace Tests\Feature\Admin\Gewichten;

use App\Models\Gewicht;
use App\Models\Plaats;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GewichtenStoreTest extends TestCase
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
        $this->gewicht = maakGewicht(["plaats_id" => $this->plaats->id]);
    }

    public function tearDown(): void
    {
        cleanUpDb("gewichten");
        $this->plaats = null;
        $this->gewicht = null;

        parent::tearDown();
    }

    /** @test */
    public function gewichtAanmaken()
    {
        $response = $this->bewaarGewicht($this->gewicht);

        $response->assertStatus(201);
        $this->assertInDatabase($this->gewicht);
    }

    /** @test */
    public function plaatsIdIsVerplicht() {
        $expectedErrorMessage = "Plaats_id is verplicht!";
        $this->gewicht->plaats_id = null;

        $response = $this->bewaarGewicht($this->gewicht);

        assertErrorMessage($this, "plaats_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function plaatsIdMoetReedsBestaan() {
        $expectedErrorMessage = "Plaats_id niet gevonden!";
        $this->gewicht->plaats_id = 666;

        $response = $this->bewaarGewicht($this->gewicht);

        assertErrorMessage($this, "plaats_id", $response, $expectedErrorMessage);
    }

    /** @test */
    public function gewichtIsVerplicht() {
        $expectedErrorMessage = "Gewicht is verplicht!";
        $this->gewicht->gewicht = null;

        $response = $this->bewaarGewicht($this->gewicht);

        assertErrorMessage($this, "gewicht", $response, $expectedErrorMessage);
    }

    /** @test */
    public function gewichtIsNumeriek() {
        $expectedErrorMessage = "Gewicht is niet numeriek!";
        $this->gewicht->gewicht = "abcd";

        $response = $this->bewaarGewicht($this->gewicht);

        assertErrorMessage($this, "gewicht", $response, $expectedErrorMessage);
    }

    /** @test */
    public function GewichtGroterDanNul() {
        $expectedErrorMessage = "Gewicht moet groter dan 0 zijn!";
        $this->gewicht->gewicht = 0;

        $response = $this->bewaarGewicht($this->gewicht);

        assertErrorMessage($this, "gewicht", $response, $expectedErrorMessage);
    }

    /** @test */
    public function geldigheidIsVerplicht() {
        $expectedErrorMessage = "Geldigheid is verplicht!";
        $this->gewicht->is_geldig = null;

        $response = $this->bewaarGewicht($this->gewicht);

        assertErrorMessage($this, "is_geldig", $response, $expectedErrorMessage);
    }

    /** @test */
    public function geldigheidIsNumeriek() {
        $expectedErrorMessage = 'Geldigheid is niet numeriek!';
        $this->gewicht->is_geldig = "abc";

        $response = $this->bewaarGewicht($this->gewicht);

        assertErrorMessage($this, "is_geldig", $response, $expectedErrorMessage);
    }

    /** @test */
    public function geldigheidTussen0En1() {
        $expectedErrorMessage = 'Geldigheid moet liggen tussen 0 en 1!';
        $this->gewicht->is_geldig = 2;

        $response = $this->bewaarGewicht($this->gewicht);

        assertErrorMessage($this, "is_geldig", $response, $expectedErrorMessage);
    }

    /**
     * @param Gewicht $gewicht
     * @return TestResponse
     */
    private function bewaarGewicht(Gewicht $gewicht): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'POST',
                    URL_GEWICHTEN_ADMIN,
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
