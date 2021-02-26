<?php

namespace Tests\Unit\Plaatsdeelnemer;


use App\Http\Resources\PlaatsdeelnemerResource;
use App\Models\Plaatsdeelnemer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaatsdeelnemerResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $plaatsdeelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->plaatsdeelnemer = bewaarPlaatsdeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function heeftEenId()
    {
        $deelnemerResource = PlaatsdeelnemerResource::collection(Plaatsdeelnemer::first()->get())->resolve();

        $this->assertEquals($this->plaatsdeelnemer->id, $deelnemerResource[0]["id"]);
    }

    /** @test */
    public function heeftEenPlaatsId()
    {
        $deelnemerResource = PlaatsdeelnemerResource::collection(Plaatsdeelnemer::first()->get())->resolve();

        $this->assertEquals($this->plaatsdeelnemer->plaats_id, $deelnemerResource[0]["plaats_id"]);
    }

    /** @test */
    public function heeftEenWedstrijddeelnemerId()
    {
        $deelnemerResource = PlaatsdeelnemerResource::collection(Plaatsdeelnemer::first()->get())->resolve();

        $this->assertEquals($this->plaatsdeelnemer->wedstrijddeelnemer_id, $deelnemerResource[0]["wedstrijddeelnemer_id"]);
    }

    /** @test */
    public function kanWegerZijn()
    {
        $deelnemerResource = PlaatsdeelnemerResource::collection(Plaatsdeelnemer::first()->get())->resolve();

        $this->assertEquals($this->plaatsdeelnemer->is_weger, $deelnemerResource[0]["is_weger"]);
    }
}
