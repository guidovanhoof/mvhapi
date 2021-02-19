<?php

namespace Tests\Unit\Deelnemer;


use App\Http\Resources\DeelnemerResource;
use App\Models\Deelnemer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeelnemerResourceTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        cleanUpDb("deelnemers");

        parent::tearDown();
    }

    /** @test */
    public function heeftEenId()
    {
        $deelnemer = bewaarDeelnemer();

        $deelnemerResource = DeelnemerResource::collection(Deelnemer::first()->get())->resolve();

        $this->assertEquals($deelnemer->id, $deelnemerResource[0]["id"]);
    }

    /** @test */
    public function heeftEenNummer()
    {
        $deelnemer = bewaarDeelnemer();

        $deelnemerResource = DeelnemerResource::collection(Deelnemer::first()->get())->resolve();

        $this->assertEquals($deelnemer->nummer, $deelnemerResource[0]["nummer"]);
    }

    /** @test */
    public function heeftEenNaam()
    {
        $deelnemer = bewaarDeelnemer();

        $deelnemerResource = DeelnemerResource::collection(Deelnemer::first()->get())->resolve();

        $this->assertEquals($deelnemer->naam, $deelnemerResource[0]["naam"]);
    }
}
