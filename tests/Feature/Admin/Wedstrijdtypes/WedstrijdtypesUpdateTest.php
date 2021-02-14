<?php

namespace Tests\Feature\Admin\Wedstrijdtypes;

use App\Models\Wedstrijdtype;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdtypesUpdateTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function wedstrijdtypeNietAanwezig()
    {
        $wedstrijdtype = maakWedstrijdtype(["id" => 666, "omschrijving" => "niet gekend"]);

        $response = $this->wijzigWedstrijdtype($wedstrijdtype);

        $response->assertStatus(404);
        $errorMessage = $response->json()["message"];
        $this->assertEquals("Wedstrijdtype niet gevonden!", $errorMessage);
    }

    /** @test */
    public function omschrijvingWijzigen()
    {
        $wedstrijdtype = bewaarWedstrijdtype();
        $wedstrijdtype->omschrijving = "nieuwe type";

        $response = $this->wijzigWedstrijdtype($wedstrijdtype);

        $response->assertStatus(200);
        $this->assertWedstrijdtypeInDatabase($wedstrijdtype);
    }

    /** @test */
    public function omschrijvingIsVerplicht() {
        $expectedErrorMessage = "Omschrijving is verplicht!";
        $wedstrijdtype = bewaarWedstrijdtype();
        $wedstrijdtype->omschrijving = null;

        $response = $this->wijzigWedstrijdtype($wedstrijdtype);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /** @test */
    public function omschrijvingIsUniek() {
        $expectedErrorMessage = "Omschrijving bestaat reeds!";
        $wedstrijdtype1 = bewaarWedstrijdtype();
        $wedstrijdtype2 = bewaarWedstrijdtype();
        $wedstrijdtype2->omschrijving = $wedstrijdtype1->omschrijving;

        $response = $this->wijzigWedstrijdtype($wedstrijdtype2);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /**
     * @param Wedstrijdtype $wedstrijdtype
     * @return array
     */
    private function dataToArray(Wedstrijdtype $wedstrijdtype): array
    {
        return ["id" => $wedstrijdtype->id, "omschrijving" => $wedstrijdtype->omschrijving];
    }

    /**
     * @param Wedstrijdtype $wedstrijdtype
     */
    private function assertWedstrijdtypeInDatabase(Wedstrijdtype $wedstrijdtype): void
    {
        $this
            ->assertDatabaseHas(
                'wedstrijdtypes',
                $this->dataToArray($wedstrijdtype)
            )
            ->assertJson($wedstrijdtype->toJson());
    }

    /**
     * @param Wedstrijdtype $wedstrijdtype
     * @return TestResponse
     */
    private function wijzigWedstrijdtype(Wedstrijdtype $wedstrijdtype): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'PUT',
                    URL_WEDSTRIJDTYPES_ADMIN . $wedstrijdtype->id,
                    ["omschrijving" => $wedstrijdtype->omschrijving]
                )
            ;
    }
}
