<?php

namespace Database\Seeders;

use App\Models\Kalender;
use App\Models\Wedstrijd;
use App\Models\Wedstrijdtype;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WedstrijdenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Kalender::all() as $kalender) {
            $startDatum = Carbon::create($kalender->jaar, 3, 1);
            foreach (Wedstrijdtype::all() as $wedstrijdtype) {
                Wedstrijd::factory()->create(
                    [
                        "kalender_id" => $kalender->id,
                        "wedstrijdtype_id" => $wedstrijdtype->id,
                        "omschrijving" => $wedstrijdtype->omschrijving,
                        "aanvang" => "13:30:00",
                        "datum" => $startDatum->format("Y-m-d"),
                    ]
                );
                $startDatum->addMonth();
            }
        }
    }
}
