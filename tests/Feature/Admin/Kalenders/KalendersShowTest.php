<?php

namespace Tests\Feature\Admin\Kalenders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class KalendersShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function kalenderNietAanwezig()
    {
        $response = $this->getKalender('1900');

        assertNietGevonden($this, $response, "Kalender");
    }

    /** @test */
    public function kalenderAanwezig()
    {
        $kalender = bewaarKalender();

        $response = $this->getKalender($kalender->jaar);

        $response->assertStatus(200);
        $data = $response->json();
        assertKalenderEquals($this, $data, $kalender);
    }

    /**
     * @param $jaar
     * @return TestResponse
     */
    private function getKalender($jaar): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'GET',
                    URL_KALENDERS_ADMIN . $jaar
                )
        ;
    }
}
