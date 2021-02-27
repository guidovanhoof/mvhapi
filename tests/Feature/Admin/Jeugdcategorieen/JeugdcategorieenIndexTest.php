<?php

namespace Tests\Feature\Admin\Jeugdcategorieen;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class JeugdcategorieenIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function geenJeugdcategoriesAanwezig()
    {
        $response = $this->getJeugdcategorieen();

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function eenJeugdcategorieAanwezig()
    {
        $jeugdcategorie = bewaarJeugdcategorie();

        $response = $this->getJeugdcategorieen();

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data);
        assertJeugdcategorieEquals($this, $data[0], $jeugdcategorie);
    }

    /**
     * @return TestResponse
     */
    private function getJeugdcategorieen(): TestResponse
    {
        $plainToken = createUserAndToken();

        return $this
            ->withHeader('Authorization', 'Bearer ' . $plainToken)
            ->json(
                'GET',
                URL_JEUGDCATEGORIEEN_ADMIN
            )
        ;
    }
}
