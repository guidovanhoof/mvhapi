<?php

namespace Tests\Unit\Jeugdcategorie;


use App\Http\Resources\Api\JeugdcategorieResource;
use App\Models\Jeugdcategorie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JeugdcategorieResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $jeugdcategorie;

    public function setUp(): void
    {
        parent::setUp();

        $this->jeugdcategorie = bewaarJeugdcategorie();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function heeftEenId()
    {
        $jeugdcategorieResource = JeugdcategorieResource::collection(Jeugdcategorie::first()->get())->resolve();

        $this->assertEquals($this->jeugdcategorie->id, $jeugdcategorieResource[0]["id"]);
    }

    /** @test */
    public function heeftEenOmschrijving()
    {
        $jeugdcategorieResource = JeugdcategorieResource::collection(Jeugdcategorie::first()->get())->resolve();

        $this->assertEquals($this->jeugdcategorie->omschrijving, $jeugdcategorieResource[0]["omschrijving"]);
    }
}
