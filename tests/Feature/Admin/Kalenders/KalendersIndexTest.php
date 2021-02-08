<?php

namespace Tests\Feature\Admin\Kalenders;

use App\Models\Kalender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class KalendersIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenKalendersAanwezig()
    {
        $response = $this->getKalenders();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function eenKalenderAanwezig()
    {
        $kalender = bewaarKalender();

        $response = $this->getKalenders();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertKalenderEquals($this, $data[0], $kalender);
    }

    /** @test */
    public function gesorteerdOpJaarAflopend()
    {
        $eerste_kalender = bewaarKalender(["jaar" => 2019]);
        $tweede_kalender = bewaarKalender(["jaar" => 2020]);

        $response = $this->getKalenders();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(2, $data);
        assertKalenderEquals($this, $data[0], $tweede_kalender);
        assertKalenderEquals($this, $data[1], $eerste_kalender);
    }

    /**
     * @return TestResponse
     */
    private function getKalenders(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_KALENDERS_ADMIN
            )
        ;
    }
}
