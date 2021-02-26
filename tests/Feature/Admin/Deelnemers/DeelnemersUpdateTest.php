<?php

namespace Tests\Feature\Admin\Deelnemers;

use App\Models\Deelnemer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeelnemersUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $deelnemer;
    private $nummer;

    public function setUp(): void
    {
        parent::setUp();

        $this->deelnemer = bewaarDeelnemer();
        $this->nummer = $this->deelnemer->nummer;
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->deelnemer = null;

        parent::tearDown();
    }

    /** @test */
    public function deelnemerWijzigen()
    {
        $this->deelnemer->nummer = 666;
        $this->deelnemer->naam = "nieuwe naam";

        $response = $this->wijzigDeelnemer($this->deelnemer, $this->nummer);

        $response->assertStatus(200);
        assertDeelnemerInDatabase($this, $this->deelnemer);
    }

    /** @test */
    public function nummerIsVerplicht() {
        $expectedErrorMessage = "Nummer is verplicht!";
        $this->deelnemer->nummer = null;

        $response = $this->wijzigDeelnemer($this->deelnemer, $this->nummer);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsNumeriek() {
        $expectedErrorMessage = "Nummer is niet numeriek!";
        $this->deelnemer->nummer = "abcd";

        $response = $this->wijzigDeelnemer($this->deelnemer, $this->nummer);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerGroterDanNul() {
        $expectedErrorMessage = "Nummer moet groter dan 0 zijn!";
        $this->deelnemer->nummer = 0;

        $response = $this->wijzigDeelnemer($this->deelnemer, $this->nummer);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nummerIsUniek() {
        $expectedErrorMessage = "Nummer bestaat reeds!";
        $deelnemer = bewaarDeelnemer();
        $this->deelnemer->nummer = $deelnemer->nummer;

        $response = $this->wijzigDeelnemer($this->deelnemer, $this->nummer);

        assertErrorMessage($this, "nummer", $response, $expectedErrorMessage);
    }

    /** @test */
    public function naamIsVerplicht() {
        $expectedErrorMessage = "Naam is verplicht!";
        $this->deelnemer->naam = null;

        $response = $this->wijzigDeelnemer($this->deelnemer, $this->nummer);

        assertErrorMessage($this, "naam", $response, $expectedErrorMessage);
    }

    /** @test */
    public function naamIsUniek() {
        $expectedErrorMessage = "Naam bestaat reeds!";
        $deelnemer = bewaarDeelnemer();
        $this->deelnemer->naam = $deelnemer->naam;

        $response = $this->wijzigDeelnemer($this->deelnemer, $this->nummer);

        assertErrorMessage($this, "naam", $response, $expectedErrorMessage);
    }

    /**
     * @param Deelnemer $deelnemer
     * @param int $nummer
     * @return TestResponse
     */
    private function wijzigDeelnemer(Deelnemer $deelnemer, int $nummer): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'PUT',
                    URL_DEELNEMERS_ADMIN . $nummer,
                    deelnemerToArry($deelnemer)
                )
        ;
    }
}
