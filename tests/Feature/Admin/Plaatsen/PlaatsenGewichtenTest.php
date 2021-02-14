<?php

namespace Tests\Feature\Admin\Plaatsen;

use App\Models\Plaats;
use App\Models\Reeks;
use App\Models\Wedstrijd;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PlaatsenGewichtenTest extends TestCase
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
        cleanUpDb("gewichten");

        parent::tearDown();
    }

    /** @test */
    public function geenGewichtenAanwezig()
    {
        $response = $this->getPlaatsGewichten($this->plaats);

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function gewichtenAanwezig()
    {
        $gewicht = bewaarGewicht(["plaats_id" => $this->plaats->id]);

        $response = $this->getPlaatsGewichten($this->plaats);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertGewichtEquals($this, $data[0], $gewicht);
    }

    /**
     * @param Plaats $plaats
     * @return TestResponse
     */
    private function getPlaatsGewichten(Plaats $plaats): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_PLAATSEN_ADMIN . $plaats->id . '/gewichten'
            )
        ;
    }
}
