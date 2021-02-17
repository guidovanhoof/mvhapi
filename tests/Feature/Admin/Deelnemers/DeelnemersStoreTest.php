<?php

namespace Tests\Feature\Admin\Deelnemers;

use App\Models\Deelnemer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeelnemersStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $deelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->deelnemer = maakDeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb("deelnemers");
        $this->deelnemer = null;

        parent::tearDown();
    }

    /** @test */
    public function deelnemerAanmaken()
    {
        $response = $this->bewaarDeelnemer($this->deelnemer);
        $response->assertStatus(201);
        $this->assertInDatabase($this->deelnemer);
    }

    /** @test */
    public function nummerIsVerplicht() {
        $expectedErrorMessage = "Nummer is verplicht!";
        $this->deelnemer->nummer = null;

        $response = $this->bewaarDeelnemer($this->deelnemer);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }
    /** @test */
    public function nummerIsNumeriek() {
        $expectedErrorMessage = "Nummer is niet numeriek!";
        $this->deelnemer->nummer = "abcd";

        $response = $this->bewaarDeelnemer($this->deelnemer);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerGroterDanNul() {
        $expectedErrorMessage = "Nummer moet groter dan 0 zijn!";
        $this->deelnemer->nummer = 0;

        $response = $this->bewaarDeelnemer($this->deelnemer);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsUniek() {
        $expectedErrorMessage = "Nummer bestaat reeds!";
        $deelnemer = bewaarDeelnemer();
        $this->deelnemer->nummer = $deelnemer->nummer;

        $response = $this->bewaarDeelnemer($this->deelnemer);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function naamIsVerplicht() {
        $expectedErrorMessage = "Naam is verplicht!";
        $this->deelnemer->naam = null;

        $response = $this->bewaarDeelnemer($this->deelnemer);

        assertErrorMessage($this, "naam", $response, $expectedErrorMessage);
    }

    /** @test */
    public function naamIsUniek() {
        $expectedErrorMessage = "Naam bestaat reeds!";
        $deelnemer = bewaarDeelnemer();
        $this->deelnemer->naam = $deelnemer->naam;

        $response = $this->bewaarDeelnemer($this->deelnemer);

        assertErrorMessage($this, "naam", $response, $expectedErrorMessage);
    }

    /**
     * @param Deelnemer $deelnemer
     * @return TestResponse
     */
    private function bewaarDeelnemer(Deelnemer $deelnemer): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'POST',
                    URL_DEELNEMERS_ADMIN,
                    deelnemerToArry($deelnemer)
                )
        ;
    }

    /**
     * @param Deelnemer $deelnemer
     */
    private function assertInDatabase(Deelnemer $deelnemer): void
    {
        $this
            ->assertDatabaseHas(
                'deelnemers',
                deelnemerToArry($deelnemer)
            )
            ->assertJson($deelnemer->toJson())
        ;
    }
}
