<?php

namespace Tests\Unit\Wedstrijddeelnemer;


use App\Http\Resources\WedstrijddeelnemerResource;
use App\Models\Deelnemer;
use App\Models\Wedstrijddeelnemer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WedstrijddeelnemerResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $wedstrijddeelnemer;

    public function setUp(): void
    {
        parent::setUp();

        $this->wedstrijddeelnemer = bewaarWedstrijddeelnemer();
    }

    public function tearDown(): void
    {
        cleanUpDb();

        parent::tearDown();
    }

    /** @test */
    public function heeftEenId()
    {
        $deelnemerResource = WedstrijddeelnemerResource::collection(Wedstrijddeelnemer::first()->get())->resolve();

        $this->assertEquals($this->wedstrijddeelnemer->id, $deelnemerResource[0]["id"]);
    }

    /** @test */
    public function heeftEenWedstrijdId()
    {
        $deelnemerResource = WedstrijddeelnemerResource::collection(Wedstrijddeelnemer::first()->get())->resolve();

        $this->assertEquals($this->wedstrijddeelnemer->wedstrijd_id, $deelnemerResource[0]["wedstrijd_id"]);
    }

    /** @test */
    public function heeftEenDeelnemerId()
    {
        $deelnemerResource = WedstrijddeelnemerResource::collection(Wedstrijddeelnemer::first()->get())->resolve();

        $this->assertEquals($this->wedstrijddeelnemer->deelnemer_id, $deelnemerResource[0]["deelnemer_id"]);
    }

    /** @test */
    public function heeftEenOpmerkingen()
    {
        $deelnemerResource = WedstrijddeelnemerResource::collection(Wedstrijddeelnemer::first()->get())->resolve();

        $this->assertEquals($this->wedstrijddeelnemer->opmerkingen, $deelnemerResource[0]["opmerkingen"]);
    }
}
