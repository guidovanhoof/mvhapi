<?php

namespace Tests\Feature\Admin\Plaatsdeelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsdeelnemersShowTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function geenplaatsdeelnemerAanwezig()
    {
        $response = $this->getPlaatsdeelnemer(666);

        assertNietGevonden($this, $response, "Plaatsdeelnemer");
    }

    /** @test */
    public function plaatsdeelnemerAanwezig()
    {
        $plaatsdeelnemer = bewaarPlaatsdeelnemer();

        $response = $this->getPlaatsdeelnemer($plaatsdeelnemer->id);

        $response->assertStatus(200);
        assertPlaatsdeelnemerEquals($this, $response->json(), $plaatsdeelnemer);
    }

    /**
     * @param int $id
     * @return TestResponse
     */
    private function getPlaatsdeelnemer(int $id): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_PLAATSDEELNEMERS_ADMIN . $id
            )
            ;
    }
}
