<?php

namespace Tests\Feature\Admin\Wedstrijden;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdenShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function wedstrijdNietAanwezig()
    {
        $response = $this->getWedstrijd('1900-01-01');

        assertNietGevonden($this, $response, "Wedstrijd");
    }

    /** @test */
    public function wedstrijdAanwezig()
    {
        $wedstrijd = bewaarWedstrijd();

        $response = $this->getWedstrijd($wedstrijd->datum);

        $response->assertStatus(200);
        $data = $response->json();
        assertWedstrijdEquals($this, $data, $wedstrijd);
    }

    /**
     * @param $datum
     * @return TestResponse
     */
    private function getWedstrijd($datum): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'GET',
                    URL_WEDSTRIJDEN_ADMIN . $datum
                )
        ;
    }
}
