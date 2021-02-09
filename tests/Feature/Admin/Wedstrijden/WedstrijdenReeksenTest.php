<?php

namespace Tests\Feature\Admin\Wedstrijden;

use App\Models\Wedstrijd;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdenReeksenTest extends TestCase
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
        cleanUpDb("reeksen");

        parent::tearDown();
    }

    /** @test */
    public function geenReeksenAanwezig()
    {
        $response = $this->getWedstrijdReeksen($this->wedstrijd);

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function reeksenAanwezig()
    {
        $reeks = bewaarReeks(["wedstrijd_id" => $this->wedstrijd->id]);

        $response = $this->getWedstrijdReeksen($this->wedstrijd);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertReeksEquals($this, $data[0], $reeks);
    }

    /**
     * @param Wedstrijd $wedstrijd
     * @return TestResponse
     */
    private function getWedstrijdReeksen(Wedstrijd $wedstrijd): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_WEDSTRIJDEN_ADMIN . $wedstrijd->datum . '/reeksen'
            )
        ;
    }
}
