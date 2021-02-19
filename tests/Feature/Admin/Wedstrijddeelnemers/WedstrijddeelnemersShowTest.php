<?php

namespace Tests\Feature\Admin\Deelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersShowTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb("wedstrijddeelnemers");

        parent::tearDown();
    }

    /** @test */
    public function geenwedstrijddeelnemerAanwezig()
    {
        $response = $this->getWedstrijddeelnemer(666);

        $response->assertStatus(404);
        $errorMessage = $response->json()["message"];
        $this->assertEquals("Wedstrijddeelnemer niet gevonden!", $errorMessage);
    }

    /** @test */
    public function wedstrijddeelnemerAanwezig()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer();

        $response = $this->getWedstrijddeelnemer($wedstrijddeelnemer->id);

        $response->assertStatus(200);
        $data = $response->json();
        assertWedstrijddeelnemerEquals($this, $data, $wedstrijddeelnemer);
    }

    /**
     * @param int $id
     * @return TestResponse
     */
    private function getWedstrijddeelnemer(int $id): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_WEDSTRIJDDEELNEMERS_ADMIN . $id
            )
            ;
    }
}
