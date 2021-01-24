<?php

namespace Tests\Feature\Admin\Wedstrijdtypes;

use App\Models\Wedstrijdtype;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class WedstrijdtypesStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function wedstrijdtypeAanmaken()
    {
        $wedstrijdtype = maakWedstrijdtype();

        $response = $this->bewaarWedstrijdtype($wedstrijdtype);

        $response->assertStatus(201);
        $this
            ->assertDatabaseHas(
                'wedstrijdtypes',
                $wedstrijdtype->toArray()
            )
            ->assertJson($wedstrijdtype->toJson())
        ;
    }

    /** @test */
    public function omschrijvingIsVerplicht() {
        $expectedErrorMessage = "Omschrijving is verplicht!";
        $wedstrijdtype = maakWedstrijdtype(['omschrijving' => null]);

        $response = $this->bewaarWedstrijdtype($wedstrijdtype);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /** @test */
    public function omschrijvingIsUniek() {
        $expectedErrorMessage = "Omschrijving bestaat reeds!";
        $wedstrijdtype = bewaarWedstrijdtype();

        $response = $this->bewaarWedstrijdtype($wedstrijdtype);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /**
     * @param Wedstrijdtype $wedstrijdtype
     * @return TestResponse
     */
    private function bewaarWedstrijdtype(Wedstrijdtype $wedstrijdtype): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'POST',
                    URL_WEDSTRIJDTYPES_ADMIN,
                    ["omschrijving" => $wedstrijdtype->omschrijving]
                )
        ;
    }
}
