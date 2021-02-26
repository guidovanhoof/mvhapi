<?php

namespace Tests\Feature\Admin\Wedstrijden;

use App\Models\Wedstrijd;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdenDeelnemersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $wedstrijd;

    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijd = bewaarWedstrijd();
    }

    public function tearDown(): void
    {
        cleanUpDb("wedstrijden");

        parent::tearDown();
    }

    /** @test */
    public function geenDeelnemersAanwezig()
    {
        $response = $this->getWedstrijddeelnemers($this->wedstrijd);

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function deelnemersAanwezig()
    {
        $wedstrijddeelnemer = bewaarWedstrijddeelnemer(["wedstrijd_id" => $this->wedstrijd->id]);

        $response = $this->getWedstrijddeelnemers($this->wedstrijd);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertWedstrijddeelnemerEquals($this, $data[0], $wedstrijddeelnemer);
    }

    /**
     * @param Wedstrijd $wedstrijd
     * @return TestResponse
     */
    private function getWedstrijddeelnemers(Wedstrijd $wedstrijd): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_WEDSTRIJDEN_ADMIN . $wedstrijd->datum . '/deelnemers'
            )
        ;
    }
}
