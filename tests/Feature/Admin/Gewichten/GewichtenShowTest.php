<?php

namespace Tests\Feature\Admin\Gewichten;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GewichtenShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenGewichtAanwezig()
    {
        $response = $this->getGewicht(666);

        assertNietGevonden($this, $response, "Gewicht");
    }

    /** @test */
    public function gewichtAanwezig()
    {
        $gewicht = bewaarGewicht();

        $response = $this->getGewicht($gewicht->id);

        $response->assertStatus(200);
        $data = $response->json();
        assertGewichtEquals($this, $data, $gewicht);
    }

    /**
     * @param int $id
     * @return TestResponse
     */
    private function getGewicht(int $id): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_GEWICHTEN_ADMIN . $id
            )
            ;
    }
}
