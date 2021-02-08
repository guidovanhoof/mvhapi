<?php

namespace Tests\Unit\Plaats;


use App\Http\Resources\PlaatsResource;
use App\Models\Kalender;
use App\Models\Plaats;
use App\Models\Reeks;
use App\Models\Wedstrijd;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaatsResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $plaats = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->plaats = bewaarPlaats();
    }

    public function tearDown(): void
    {
        cleanUpDb("plaatsen");
        $this->plaats = null;

        parent::tearDown();
    }

    /** @test  */
    public function heeftEenId()
    {
        $plaatsResource = PlaatsResource::collection(Plaats::first()->get())->resolve();

        $this->assertEquals($this->plaats->id, $plaatsResource[0]["id"]);
    }

    /** @test  */
    public function heeftEenReeksId()
    {
        $plaatsResource = PlaatsResource::collection(Plaats::first()->get())->resolve();

        $this->assertEquals($this->plaats->reeks_id, $plaatsResource[0]["reeks_id"]);
    }

    /** @test  */
    public function heeftEenNummer()
    {
        $plaatsResource = PlaatsResource::collection(Plaats::first()->get())->resolve();

        $this->assertEquals($this->plaats->nummer, $plaatsResource[0]["nummer"]);
    }

    /** @test  */
    public function heeftOpmerkingen()
    {
        $plaatsResource = PlaatsResource::collection(Plaats::first()->get())->resolve();

        $this->assertEquals($this->plaats->opmerkingen, $plaatsResource[0]["opmerkingen"]);
    }
}
