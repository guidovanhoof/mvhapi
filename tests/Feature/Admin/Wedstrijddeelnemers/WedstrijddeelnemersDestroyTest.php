<?php

namespace Tests\Feature\Admin\Wedstrijddeelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb("wedstrijddeelnemers");

        parent::tearDown();
    }

    /** @test */
    public function wedstrijddeelnemerNietAanwezig()
    {
        $response = $this->verwijderWedstrijddeelnemer(666);

        assertNietGevonden($this, $response,"Wedstrijddeelnemer");
    }

    /** @test */
    public function wedstrijddeelnemerAanwezig()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();

        $response = $this->verwijderWedstrijddeelnemer($wedstrijddeelnemer->id);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "wedstrijddeelnemers",
                wedstrijddeelnemerToArry($wedstrijddeelnemer)
            )
            ->assertEquals("Wedstrijddeelnemer verwijderd!", $errorMessage)
        ;
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function verwijderWedstrijddeelnemer($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_WEDSTRIJDDEELNEMERS_ADMIN . $id
                )
            ;
    }
}
