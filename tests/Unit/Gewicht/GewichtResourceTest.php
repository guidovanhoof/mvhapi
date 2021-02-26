<?php

namespace Tests\Unit\Gewicht;

use App\Http\Resources\GewichtResource;
use App\Models\Gewicht;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GewichtResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $gewicht = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->gewicht = bewaarGewicht();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->gewicht = null;

        parent::tearDown();
    }

    /** @test  */
    public function heeftEenId()
    {
        $gewichtResource = GewichtResource::collection(Gewicht::first()->get())->resolve();

        $this->assertEquals($this->gewicht->id, $gewichtResource[0]["id"]);
    }

    /** @test  */
    public function heeftEenPlaatsId()
    {
        $gewichtResource = GewichtResource::collection(Gewicht::first()->get())->resolve();

        $this->assertEquals($this->gewicht->plaats_id, $gewichtResource[0]["plaats_id"]);
    }

    /** @test  */
    public function heeftEenGewicht()
    {
        $gewichtResource = GewichtResource::collection(Gewicht::first()->get())->resolve();

        $this->assertEquals($this->gewicht->gewicht, $gewichtResource[0]["gewicht"]);
    }

    /** @test  */
    public function heeftEenGeldigheid()
    {
        $gewichtResource = GewichtResource::collection(Gewicht::first()->get())->resolve();

        $this->assertEquals($this->gewicht->is_geldig, $gewichtResource[0]["is_geldig"]);
    }
}
