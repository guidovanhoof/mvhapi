<?php

namespace Tests\Feature\Admin\Plaatsdeelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsdeelnemersDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function plaatsdeelnemerNietAanwezig()
    {
        $response = $this->verwijderPlaatsdeelnemer(666);

        assertNietGevonden($this, $response,"Plaatsdeelnemer");
    }

    /** @test */
    public function plaatsdeelnemerAanwezig()
    {
        $plaatsdeelnemer = bewaarPlaatsdeelnemer();

        $response = $this->verwijderPlaatsdeelnemer($plaatsdeelnemer->id);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "plaatsdeelnemers",
                plaatsdeelnemerToArry($plaatsdeelnemer)
            )
            ->assertEquals("Plaatsdeelnemer verwijderd!", $errorMessage)
        ;
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function verwijderPlaatsdeelnemer($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_PLAATSDEELNEMERS_ADMIN . $id
                )
            ;
    }
}
