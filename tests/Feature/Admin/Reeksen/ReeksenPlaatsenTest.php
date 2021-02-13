<?php

namespace Tests\Feature\Admin\Reeksen;

use App\Models\Reeks;
use App\Models\Wedstrijd;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ReeksenPlaatsenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $reeks;

    public function setUp(): void
    {
        parent::setUp();

        $this->reeks = bewaarReeks();
    }

    public function tearDown(): void
    {
        cleanUpDb("plaatsen");

        parent::tearDown();
    }

    /** @test */
    public function geenPlaatsenAanwezig()
    {
        $response = $this->getReeksPlaatsen($this->reeks);

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function plaatsenAanwezig()
    {
        $plaats = bewaarPlaats(["reeks_id" => $this->reeks->id]);

        $response = $this->getReeksPlaatsen($this->reeks);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertPlaatsEquals($this, $data[0], $plaats);
    }

    /**
     * @param Reeks $reeks
     * @return TestResponse
     */
    private function getReeksPlaatsen(Reeks $reeks): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_REEKSEN_ADMIN . $reeks->id . '/plaatsen'
            )
        ;
    }
}
