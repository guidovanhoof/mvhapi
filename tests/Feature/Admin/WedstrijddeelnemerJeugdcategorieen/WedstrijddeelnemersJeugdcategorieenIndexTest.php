<?php

namespace Tests\Feature\Admin\WedstrijddeelnemerJeugdcategorieen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersJeugdcategorieenIndexTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function geenWedstrijddeelnemerJeugdcategorieenAanwezig()
    {
        $response = $this->getWedstrijddeelnemerJeugdcategorieen();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function wedstrijddeelnemerJeugdcategorieJeugdcategorieenAanwezig()
    {
        $wedstrijddeelnemerJeugdcategorie = bewaarWedstrijddeelnemerJeugdcategorie();

        $response = $this->getWedstrijddeelnemerJeugdcategorieen();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertWedstrijddeelnemerJeugdcategorieEquals($this, $data[0], $wedstrijddeelnemerJeugdcategorie);
    }

    /**
     * @return TestResponse
     */
    private function getWedstrijddeelnemerJeugdcategorieen(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_WEDSTRIJDDEELNEMERJEUGDCATEGORIEEN_ADMIN
            )
        ;
    }
}
