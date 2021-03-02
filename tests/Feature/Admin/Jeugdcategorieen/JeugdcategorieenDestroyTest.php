<?php

namespace Tests\Feature\Admin\Jeugdcategorieen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class JeugdcategorieenDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function jeugdcategorieNietAanwezig()
    {
        $response = $this->deleteJeugdcategorie(666);

        assertNietGevonden($this, $response, "Jeugdcategorie");
    }

    /** @test */
    public function jeugdcategorieAanwezig()
    {
        $jeugdcategorie = bewaarJeugdcategorie();

        $response = $this->deleteJeugdcategorie($jeugdcategorie->id);

        $response->assertStatus(200);
        $errorMessage = $response->json()["message"];
        $this
            ->assertDatabaseMissing(
                "jeugdcategorieen",
                jeugdcategorieToArray($jeugdcategorie)
            )
            ->assertEquals("Jeugdcategorie verwijderd!", $errorMessage)
        ;
    }

//    /** @test */
//    public function nogWedstrijddeelnemersGekoppeld()
//    {
//        $expectedMessage = "Jeugdcategorie niet verwijderd! Nog wedstrijddeelnemers aanwezig!";
//        $jeugdcategorie = bewaarJeugdcategorie();
//        bewaarWedstrijd(["jeugdcategorie_id" => $jeugdcategorie->id]);
//
//        $response = $this->deleteJeugdcategorie($jeugdcategorie->id);
//
//        $response->assertStatus(405);
//        $errorMessage = $response->json()["message"];
//        $this
//            ->assertDatabaseHas(
//                "jeugdcategories",
//                jeugdcategorieToArray($jeugdcategorie)
//            )
//            ->assertEquals($expectedMessage, $errorMessage)
//        ;
//    }

    /**
     * @param $id
     * @return TestResponse
     */
    public function deleteJeugdcategorie($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'DELETE',
                    URL_JEUGDCATEGORIEEN_ADMIN . $id,
                )
        ;
    }
}
