<?php

namespace Tests\Unit\GetrokkenMaat;


use App\Http\Resources\GetrokkenMaatResource;
use App\Models\GetrokkenMaat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetrokkenMaatResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $getrokkenMaat;

    public function setUp(): void
    {
        parent::setUp();

        $this->getrokkenMaat = bewaarGetrokkenMaat();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function heeftEenId()
    {
        $getrokkenMaatResource =
            GetrokkenMaatResource::collection(
                GetrokkenMaat::first()->get()
            )->resolve();

        $this->assertEquals(
            $this->getrokkenMaat->id,
            $getrokkenMaatResource[0]["id"]
        );
    }

    /** @test */
    public function heeftEenWedstrijddeelnemerId()
    {
        $getrokkenMaatResource =
            GetrokkenMaatResource::collection(
                GetrokkenMaat::first()->get()
            )->resolve();

        $this->assertEquals(
            $this->getrokkenMaat->wedstrijddeelnemer_id,
            $getrokkenMaatResource[0]["wedstrijddeelnemer_id"]
        );
    }

    /** @test */
    public function heeftEenGetrokkenMaatId()
    {
        $getrokkenMaatResource =
            GetrokkenMaatResource::collection(
                GetrokkenMaat::first()->get()
            )->resolve();

        $this->assertEquals(
            $this->getrokkenMaat->getrokken_maat_id,
            $getrokkenMaatResource[0]["getrokken_maat_id"]
        );
    }
}
