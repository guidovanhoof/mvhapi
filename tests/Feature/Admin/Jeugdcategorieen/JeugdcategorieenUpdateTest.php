<?php

namespace Tests\Feature\Admin\Jeugdcategorieen;

use App\Models\Jeugdcategorie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class JeugdcategorieenUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $jeugdcategorie;

    public  function setUp(): void
    {
        parent::setUp();

        $this->jeugdcategorie = bewaarJeugdcategorie();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->jeugdcategorie = null;

        parent::tearDown();
    }

    /** @test */
    public function jeugdcategorieNietAanwezig()
    {
        $this->jeugdcategorie->id = 666;

        $response = $this->wijzigJeugdcategorie($this->jeugdcategorie);

        assertNietGevonden($this, $response, 'Jeugdcategorie');
    }

    /** @test */
    public function jeugdcategorieWijzigen()
    {
        $this->jeugdcategorie->omschrijving = "nieuwe omschrijving";

        $response = $this->wijzigJeugdcategorie($this->jeugdcategorie);

        $response->assertStatus(200);
        assertJeugdcategorieInDatabase($this, $this->jeugdcategorie);
        $this->assertJson($this->jeugdcategorie->toJson());
    }

    /** @test */
    public function omschrijvingIsVerplicht() {
        $expectedErrorMessage = "Omschrijving is verplicht!";
        $this->jeugdcategorie->omschrijving = null;

        $response = $this->wijzigJeugdcategorie($this->jeugdcategorie);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /** @test */
    public function omschrijvingIsUniek() {
        $expectedErrorMessage = "Omschrijving bestaat reeds!";
        $jeugdcategorie = bewaarJeugdcategorie();
        $this->jeugdcategorie->omschrijving = $jeugdcategorie->omschrijving;

        $response = $this->wijzigJeugdcategorie($this->jeugdcategorie);

        assertErrorMessage($this, "omschrijving", $response, $expectedErrorMessage);
    }

    /** @test */
    public function nietsGewijzgid()
    {
        $response = $this->wijzigJeugdcategorie($this->jeugdcategorie);

        $this->assertEquals(200, $response->status());
        assertJeugdcategorieInDatabase($this, $this->jeugdcategorie);
        $this->assertJson($this->jeugdcategorie->toJson());
    }

    /**
     * @param Jeugdcategorie $jeugdcategorie
     * @return TestResponse
     */
    private function wijzigJeugdcategorie(Jeugdcategorie $jeugdcategorie): TestResponse
    {
        $plainToken = createUserAndToken();

        return
            $this
                ->withHeader('Authorization', 'Bearer ' . $plainToken)
                ->json(
                    'PUT',
                    URL_JEUGDCATEGORIEEN_ADMIN . $jeugdcategorie->id,
                    jeugdcategorieToArry($jeugdcategorie)
                )
            ;
    }
}
