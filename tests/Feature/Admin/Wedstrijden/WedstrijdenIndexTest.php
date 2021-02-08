<?php

namespace Tests\Feature\Admin\Wedstrijden;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdenIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenWedstrijdenAanwezig()
    {
        $response = $this->getWedstrijden();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function wedstrijdenAanwezig()
    {
        $wedstrijd = bewaarWedstrijd();

        $response = $this->getWedstrijden();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertWedstrijdEquals($this, $data[0], $wedstrijd);
    }

    /** @test */
    public function gesorteerdOpJaarAflopend()
    {
        $jaar = date('Y');
        $kalender = bewaarKalender(["jaar" => $jaar]);
        $eerste_wedstrijd = bewaarWedstrijd(
            [
                "kalender_id" => $kalender->id,
                "datum" => $jaar . "-04-28",
            ]
        );
        $tweede_wedstrijd = bewaarWedstrijd(
            [
                "kalender_id" => $kalender->id,
                "datum" => $jaar . "-05-28",
            ]
        );

        $response = $this->getWedstrijden();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(2, $data);
        assertWedstrijdEquals($this, $data[0], $tweede_wedstrijd);
        assertWedstrijdEquals($this, $data[1], $eerste_wedstrijd);
    }

    /**
     * @return TestResponse
     */
    private function getWedstrijden(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_WEDSTRIJDEN_ADMIN
            )
        ;
    }
}
