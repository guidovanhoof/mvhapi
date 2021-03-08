<?php

namespace Tests\Feature\Admin\WedstrijddeelnemerJeugdcategorieen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijddeelnemersJeugdcategorieenDestroyTest extends TestCase
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
        $response = $this->verwijderWedstrijddeelnemerJeugdcategorie(666);

        assertNietGevonden($this, $response,'WedstrijddeelnemerJeugdcategorie');
    }

    /** @test */
    public function wedstrijddeelnemerJeugdcategorieJeugdcategorieAanwezig()
    {
        $wedstrijddeelnemerJeugdcategorie = bewaarWedstrijddeelnemerJeugdcategorie();

        $response = $this->verwijderWedstrijddeelnemerJeugdcategorie(
            $wedstrijddeelnemerJeugdcategorie->id
        );

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "wdeelnemers_jcategorieen",
                wedstrijddeelnemerJeugdcategorieToArrayw($wedstrijddeelnemerJeugdcategorie)
            )
            ->assertEquals("WedstrijddeelnemerJeugdcategorie verwijderd!", $errorMessage)
        ;
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function verwijderWedstrijddeelnemerJeugdcategorie($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'DELETE',
                URL_WEDSTRIJDDEELNEMERJEUGDCATEGORIEEN_ADMIN . $id
            )
        ;
    }
}
