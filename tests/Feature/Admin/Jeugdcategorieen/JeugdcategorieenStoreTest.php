<?php

namespace Tests\Feature\Admin\Jeugdcategorieen;

use App\Models\Jeugdcategorie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class JeugdcategorieenStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $jeugdcategorie;

    public  function setUp(): void
    {
        parent::setUp();

        $this->jeugdcategorie = maakJeugdcategorie();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->jeugdcategorie = null;

        parent::tearDown();
    }

    /** @test */
    public function jeugdcategorieAanmaken()
    {
        $response = $this->bewaarJeugdcategorie($this->jeugdcategorie);

        $response->assertStatus(201);
        $this
            ->assertDatabaseHas(
                'jeugdcategorieen',
                jeugdcategorieToArry($this->jeugdcategorie)
            )
            ->assertJson($this->jeugdcategorie->toJson())
        ;
    }

    /** @test */
    public function omschrijvingIsVerplicht() {
        $expectedErrorMessage = "Omschrijving is verplicht!";
        $jeugdcategorie = maakJeugdcategorie(['omschrijving' => null]);

        $response = $this->bewaarJeugdcategorie($jeugdcategorie);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /** @test */
    public function omschrijvingIsUniek() {
        $expectedErrorMessage = "Omschrijving bestaat reeds!";
        $jeugdcategorie = bewaarJeugdcategorie();

        $response = $this->bewaarJeugdcategorie($jeugdcategorie);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /**
     * @param Jeugdcategorie $jeugdcategorie
     * @return TestResponse
     */
    private function bewaarJeugdcategorie(Jeugdcategorie $jeugdcategorie): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'POST',
                    URL_JEUGDCATEGORIEEN_ADMIN,
                    ["omschrijving" => $jeugdcategorie->omschrijving]
                )
            ;
    }
}
