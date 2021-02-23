<?php

namespace Database\Seeders;

use App\Models\Plaats;
use App\Models\Plaatsdeelnemer;
use App\Models\Reeks;
use App\Models\Wedstrijd;
use App\Models\Wedstrijddeelnemer;
use Illuminate\Database\Seeder;

class PlaatsdeelnemerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Wedstrijd::all() as $wedstrijd) {
            foreach (Wedstrijddeelnemer::where('wedstrijd_id', $wedstrijd->id)->get() as $wedstrijddeelnemer) {
                foreach (Reeks::where('wedstrijd_id', $wedstrijd->id)->get() as $reeks) {
                    foreach (Plaats::where('reeks_id', $reeks->id)->get() as $plaats) {
                        Plaatsdeelnemer::factory()->create(
                            [
                                'plaats_id' => $plaats->id,
                                'wedstrijddeelnemer_id' => $wedstrijddeelnemer->id,
                            ]
                        );
                    }
                }
            }
        }
    }
}
