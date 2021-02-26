<?php

namespace Tests\Feature\Admin\Wedstrijddeelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersShowTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function geenwedstrijddeelnemerAanwezig()
    {
        $response = $this->getWedstrijddeelnemer(666);

        assertNietGevonden($this, $response, "Wedstrijddeelnemer");
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
