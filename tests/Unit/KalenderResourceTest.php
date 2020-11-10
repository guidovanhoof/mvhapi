<?php

namespace Tests\Unit;


use App\Http\Resources\KalenderResource;
use App\Models\Kalender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KalenderResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function heeftEenJaar()
    {
        $jaar = date('Y');
        bewaarKalender(['jaar' => $jaar]);

        $kalenderResource = KalenderResource::collection(Kalender::first()->get())->resolve();

        $this->assertEquals($jaar, $kalenderResource[0]["jaar"]);
    }

    /** @test  */
    public function heeftEenOmschrijving()
    {
        $kalender =  bewaarKalender();

        $kalenderResource = KalenderResource::collection(Kalender::first()->get())->resolve();

        $this->assertEquals($kalender->omschrijving(), $kalenderResource[0]["omschrijving"]);
    }

    /** @test  */
    public function heeftOpmerkingen()
    {
        $kalender =  bewaarKalender();

        $kalenderResource = KalenderResource::collection(Kalender::first()->get())->resolve();

        $this->assertEquals($kalender->opmerkingen, $kalenderResource[0]["opmerkingen"]);
    }
}
