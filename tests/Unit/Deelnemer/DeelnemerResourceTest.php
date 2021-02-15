<?php

namespace Tests\Unit\Deelnemer;


use App\Http\Resources\DeelnemerResource;
use App\Http\Resources\WedstrijdtypeResource;
use App\Models\Deelnemer;
use App\Models\Wedstrijdtype;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeelnemerResourceTest extends TestCase
{
    use RefreshDatabase;

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
