<?php

namespace Tests\Feature\Admin\Kalenders;

use App\Models\Kalender;
use App\Models\Wedstrijd;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class KalendersWedstrijdenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $kalender;

    public function setUp(): void
    {
        parent::setUp();

        $this->kalender = bewaarKalender();
    }

    public function tearDown(): void
    {
        Wedstrijd::query()->delete();
        Kalender::query()->delete();

        parent::tearDown();
    }

    /** @test */
    public function geenWedstrijdenAanwezig()
    {
        $response = $this->getKalenderWedstrijden($this->kalender);

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function wedstrijdenAanwezig()
    {
        $wedstrijd = bewaarWedstrijd(["kalender_id" => $this->kalender->id]);

        $response = $this->getKalenderWedstrijden($this->kalender);

        $response->assertStatus(200);
        $data = $response->json()["data"];
        $this->assertCount(1, $data);
        assertWedstrijdEquals($this, $data[0], $wedstrijd);
    }

    /**
     * @param Kalender $kalender
     * @return TestResponse
     */
    private function getKalenderWedstrijden(Kalender $kalender): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_KALENDERS_ADMIN . $kalender->jaar . '/wedstrijden'
            )
        ;
    }
}
