<?php

namespace Tests\Feature\Admin\Deelnemers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeelnemersShowTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function geenDeelnemerAanwezig()
    {
        $response = $this->getDeelnemer(666);

        assertNietGevonden($this, $response, "Deelnemer");
    }

    /** @test */
    public function deelnemerAanwezig()
    {
        $deelnemer = bewaarDeelnemer();

        $response = $this->getDeelnemer($deelnemer->nummer);

        $response->assertStatus(200);
        $data = $response->json();
        assertDeelnemerEquals($this, $data, $deelnemer);
    }

    /**
     * @param int $nummer
     * @return TestResponse
     */
    private function getDeelnemer(int $nummer): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_DEELNEMERS_ADMIN . $nummer
            )
            ;
    }
}
