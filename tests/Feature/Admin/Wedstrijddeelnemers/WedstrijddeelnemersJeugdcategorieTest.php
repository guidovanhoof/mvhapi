<?php

namespace Tests\Feature\Admin\Wedstrijddeelnemers;

use App\Models\Wedstrijddeelnemer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersJeugdcategorieTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $wedstrijddeelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijddeelnemer = bewaarWedstrijddeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function geenWedstrijddeelnemerAanwezig()
    {
        $this->wedstrijddeelnemer->id = 666;

        $response = $this->getWedstrijddeelnemerJeugdcategorie($this->wedstrijddeelnemer);

        assertNietGevonden($this, $response, "Wedstrijddeelnemer");
    }

    /** @test */
    public function geenJeugdcategorieAanwezig()
    {
        $response = $this->getWedstrijddeelnemerJeugdcategorie($this->wedstrijddeelnemer);

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function jeugdcategorieAanwezig()
    {
        $jeugdcategorie = bewaarJeugdcategorie();
        bewaarWedstrijddeelnemerJeugdcategorie(
            [
                'wedstrijddeelnemer_id' => $this->wedstrijddeelnemer->id,
                'jeugdcategorie_id' => $jeugdcategorie->id,
            ]
        );

        $response = $this->getWedstrijddeelnemerJeugdcategorie($this->wedstrijddeelnemer);

        $response->assertStatus(200);
        $data = $response->json();
        assertJeugdcategorieEquals($this, $data, $jeugdcategorie);
    }

    /**
     * @param Wedstrijddeelnemer $wedstrijddeelnemer
     * @return TestResponse
     */
    private function getWedstrijddeelnemerJeugdcategorie(Wedstrijddeelnemer $wedstrijddeelnemer): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_WEDSTRIJDDEELNEMERS_ADMIN . $wedstrijddeelnemer->id . '/jeugdcategorie'
            )
        ;
    }
}
