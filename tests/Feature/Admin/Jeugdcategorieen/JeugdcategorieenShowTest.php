<?php

namespace Tests\Feature\Admin\Jeugdcategorieen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class JeugdcategorieenShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function jeugdcategoriesBestaatNieet()
    {
        $response = $this->getJeugdcategorie(666);

        assertNietGevonden($this, $response, "Jeugdcategorie");
    }

    /** @test */
    public function jeugdcategorieBestaat()
    {
        $jeugdcategorie = bewaarJeugdcategorie();

        $response = $this->getJeugdcategorie($jeugdcategorie->id);

        $response->assertStatus(200);
        $data = $response->json();
        assertJeugdcategorieEquals($this, $data, $jeugdcategorie);
    }

    /**
     * @param $id
     * @return TestResponse
     */
    private function getJeugdcategorie($id): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_JEUGDCATEGORIEEN_ADMIN . $id
            )
        ;
    }
}
