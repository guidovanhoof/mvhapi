<?php

namespace Tests\Feature\Admin\WedstrijddeelnemerJeugdcategorieen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersJeugdcategorieenShowTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function geenWedstrijddeelnemerJeugdcategorieAanwezig()
    {
        $response = $this->getWedstrijddeelnemerJeugdcategorie(666);

        assertNietGevonden($this, $response,'WedstrijddeelnemerJeugdcategorie');
    }

    /** @test */
    public function wedstrijddeelnemerJeugdcategorieJeugdcategorieAanwezig()
    {
        $wedstrijddeelnemerJeugdcategorie = bewaarWedstrijddeelnemerJeugdcategorie();

        $response = $this->getWedstrijddeelnemerJeugdcategorie(
            $wedstrijddeelnemerJeugdcategorie->id
        );

        $response->assertStatus(200);
        $data = $response->json();
        assertWedstrijddeelnemerJeugdcategorieEquals($this, $data, $wedstrijddeelnemerJeugdcategorie);
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function getWedstrijddeelnemerJeugdcategorie($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_WEDSTRIJDDEELNEMERJEUGDCATEGORIEEN_ADMIN . $id
            )
        ;
    }
}
