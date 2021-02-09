<?php

namespace Tests\Unit\Wedstrijdtype;


use App\Http\Resources\WedstrijdtypeResource;
use App\Models\Wedstrijdtype;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WedstrijdtypeResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function heeftEenId()
    {
        $wedstrijdtype = bewaarWedstrijdtype();

        $wedstrijdtypeResource = WedstrijdtypeResource::collection(Wedstrijdtype::first()->get())->resolve();

        $this->assertEquals($wedstrijdtype->id, $wedstrijdtypeResource[0]["id"]);
    }

    /** @test */
    public function heeftEenOmschrijving()
    {
        $wedstrijdtype = bewaarWedstrijdtype();

        $wedstrijdtypeResource = WedstrijdtypeResource::collection(Wedstrijdtype::first()->get())->resolve();

        $this->assertEquals($wedstrijdtype->omschrijving, $wedstrijdtypeResource[0]["omschrijving"]);
    }
}
