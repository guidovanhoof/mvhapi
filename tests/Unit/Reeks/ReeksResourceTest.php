<?php

namespace Tests\Unit\Reeks;

use App\Http\Resources\Api\ReeksResource;
use App\Models\Reeks;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReeksResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model|mixed
     */
    private $reeks = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->reeks = bewaarReeks();
    }

    public function tearDown(): void
    {
        cleanUpDb();
        $this->reeks = null;

        parent::tearDown();
    }

    /** @test  */
    public function heeftEenId()
    {
        $reeksResource = ReeksResource::collection(Reeks::first()->get())->resolve();

        $this->assertEquals($this->reeks->id, $reeksResource[0]["id"]);
    }

    /** @test  */
    public function heeftEenWedstrijdId()
    {
        $reeksResource = ReeksResource::collection(Reeks::first()->get())->resolve();

        $this->assertEquals($this->reeks->wedstrijd_id, $reeksResource[0]["wedstrijd_id"]);
    }

    /** @test  */
    public function heeftEenNummer()
    {
        $reeksResource = ReeksResource::collection(Reeks::first()->get())->resolve();

        $this->assertEquals($this->reeks->nummer, $reeksResource[0]["nummer"]);
    }

    /** @test  */
    public function heeftEenAanvang()
    {
        $reeksResource = ReeksResource::collection(Reeks::first()->get())->resolve();

        $this->assertEquals($this->reeks->aanvang, $reeksResource[0]["aanvang"]);
    }

    /** @test  */
    public function heeftEenDuur()
    {
        $reeksResource = ReeksResource::collection(Reeks::first()->get())->resolve();

        $this->assertEquals($this->reeks->duur, $reeksResource[0]["duur"]);
    }

    /** @test  */
    public function heeftEenGewichtZak()
    {
        $reeksResource = ReeksResource::collection(Reeks::first()->get())->resolve();

        $this->assertEquals($this->reeks->gewicht_zak, $reeksResource[0]["gewicht_zak"]);
    }

    /** @test  */
    public function heeftOpmerkingen()
    {
        $reeksResource = ReeksResource::collection(Reeks::first()->get())->resolve();

        $this->assertEquals($this->reeks->opmerkingen, $reeksResource[0]["opmerkingen"]);
    }
}
