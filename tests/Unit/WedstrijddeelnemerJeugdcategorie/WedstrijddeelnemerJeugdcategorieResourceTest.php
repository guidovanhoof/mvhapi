<?php

namespace Tests\Unit\WedstrijddeelnemerJeugdcategorie;


use App\Http\Resources\Api\WedstrijddeelnemerJeugdcategorieResource;
use App\Models\WedstrijddeelnemerJeugdcategorie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WedstrijddeelnemerJeugdcategorieResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $wedstrijddeelnemerJeugdcategorie;

    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijddeelnemerJeugdcategorie = bewaarWedstrijddeelnemerJeugdcategorie();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function heeftEenId()
    {
        $wedstrijddeelnemerjeugdcategorieResource =
            WedstrijddeelnemerJeugdcategorieResource::collection(
                WedstrijddeelnemerJeugdcategorie::first()->get()
            )->resolve();

        $this->assertEquals(
            $this->wedstrijddeelnemerJeugdcategorie->id,
            $wedstrijddeelnemerjeugdcategorieResource[0]["id"]
        );
    }

    /** @test */
    public function heeftEenWedstrijddeelnemerId()
    {
        $wedstrijddeelnemerjeugdcategorieResource =
            WedstrijddeelnemerJeugdcategorieResource::collection(
                WedstrijddeelnemerJeugdcategorie::first()->get()
            )->resolve();

        $this->assertEquals(
            $this->wedstrijddeelnemerJeugdcategorie->wedstrijddeelnemer_id,
            $wedstrijddeelnemerjeugdcategorieResource[0]["wedstrijddeelnemer_id"]
        );
    }

    /** @test */
    public function heeftEenJeugdcategorieId()
    {
        $wedstrijddeelnemerjeugdcategorieResource =
            WedstrijddeelnemerJeugdcategorieResource::collection(
                WedstrijddeelnemerJeugdcategorie::first()->get()
            )->resolve();

        $this->assertEquals(
            $this->wedstrijddeelnemerJeugdcategorie->jeugdcategorie_id,
            $wedstrijddeelnemerjeugdcategorieResource[0]["jeugdcategorie_id"]
        );
    }
}
