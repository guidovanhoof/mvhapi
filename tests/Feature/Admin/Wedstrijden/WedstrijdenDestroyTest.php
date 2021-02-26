<?php

namespace Tests\Feature\Admin\Wedstrijden;

use App\Models\Wedstrijd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdenDestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $wedstrijd;

    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijd = bewaarWedstrijd();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function wedstrijdNietAanwezig()
    {
        $response = $this->verwijderWedstrijd('1900-01-01');

        assertNietGevonden($this, $response, "Wedstrijd");
    }

    /** @test */
    public function wedstrijdAanwezig()
    {
        $wedstrijd = bewaarWedstrijd();

        $response = $this->verwijderWedstrijd($wedstrijd->datum);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "wedstrijden",
                [
                    "kalender_id" => $wedstrijd->kalender_id,
                    "datum" => $wedstrijd->datum,
                    "omschrijving" => $wedstrijd->omschrijving,
                    "aanvang" => $wedstrijd->aanvang,
                ]
            )
            ->assertEquals("Wedstrijd verwijderd!", $errorMessage)
        ;
    }

    /** @test */
    public function nogReeksenGekoppeld()
    {
        $expectedMessage = "Wedstrijd niet verwijderd! Nog deelnemers en/of reeksen aanwezig!";
        bewaarReeks(["wedstrijd_id" => $this->wedstrijd->id]);

        $response = $this->verwijderWedstrijd($this->wedstrijd->datum);

        $response->assertStatus(405);
        $this->assertErrorMessageIs($response, $expectedMessage);
        assertWedstrijdInDatabase($this, $this->wedstrijd);
    }

    /** @test */
    public function nogDeelnemersGekoppeld()
    {
        $expectedMessage = "Wedstrijd niet verwijderd! Nog deelnemers en/of reeksen aanwezig!";
        bewaarWedstrijddeelnemer(["wedstrijd_id" => $this->wedstrijd->id]);

        $response = $this->verwijderWedstrijd($this->wedstrijd->datum);

        $response->assertStatus(405);
        $this->assertErrorMessageIs($response, $expectedMessage);
        assertWedstrijdInDatabase($this, $this->wedstrijd,);
    }

    /**
     * @param $datum
     * @return TestResponse
     */
    private function verwijderWedstrijd($datum): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_WEDSTRIJDEN_ADMIN . $datum
                )
            ;
    }

    /**
     * @param TestResponse $response
     * @param string $expectedMessage
     */
    private function assertErrorMessageIs(TestResponse $response, string $expectedMessage): void
    {
        $errorMessage = $response->json()["message"];
        $this->assertEquals($expectedMessage, $errorMessage);
    }
}
