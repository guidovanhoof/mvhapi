<?php

namespace Tests\Feature\Admin\Deelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeelnemersDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function deelnemerNietAanwezig()
    {
        $response = $this->verwijderDeelnemer(666);

        assertNietGevonden($this, $response, "Deelnemer");
    }

    /** @test */
    public function deelnemerAanwezig()
    {
        $deelnemer = bewaarDeelnemer();

        $response = $this->verwijderDeelnemer($deelnemer->nummer);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "deelnemers",
                deelnemerToArry($deelnemer)
            )
            ->assertEquals("Deelnemer verwijderd!", $errorMessage)
        ;
    }

    /** @test */
    public function nogWedstrijddeelnemersGekoppeld()
    {
        $expectedMessage = "Deelnemer niet verwijderd! Nog wedstrijddeelnemers aanwezig!";
        $deelnemer = bewaarDeelnemer();
        bewaarWedstrijddeelnemer(["deelnemer_id" => $deelnemer->id]);

        $response = $this->verwijderDeelnemer($deelnemer->nummer);

        $response->assertStatus(405);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseHas(
                "deelnemers",
                deelnemerToArry($deelnemer)
            )
            ->assertEquals($expectedMessage, $errorMessage)
        ;
    }

    /**
     * @param $nummer
     * @return TestResponse
     */
    private function verwijderDeelnemer($nummer): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_DEELNEMERS_ADMIN . $nummer
                )
            ;
    }
}
