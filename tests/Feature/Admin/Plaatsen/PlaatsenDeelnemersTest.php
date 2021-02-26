<?php

namespace Tests\Feature\Admin\Plaatsen;

use App\Models\Plaats;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsenDeelnemersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $plaats;

    public function setUp(): void
    {
        parent::setUp();

        $this->plaats = bewaarPlaats();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function geenDeelnemersAanwezig()
    {
        $response = $this->getPlaatsdeelnemers($this->plaats);

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function deelnemersAanwezig()
    {
        $plaatsdeelnemer = bewaarPlaatsdeelnemer(["plaats_id" => $this->plaats->id]);

        $response = $this->getPlaatsdeelnemers($this->plaats);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertPlaatsdeelnemerEquals($this, $data[0], $plaatsdeelnemer);
    }

    /**
     * @param Plaats $plaats
     * @return TestResponse
     */
    private function getPlaatsdeelnemers(Plaats $plaats): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_PLAATSEN_ADMIN . $plaats->id . '/deelnemers'
            )
        ;
    }
}
