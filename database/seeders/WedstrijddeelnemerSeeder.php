<?php

namespace Database\Seeders;

use App\Models\Deelnemer;
use App\Models\Wedstrijd;
use App\Models\Wedstrijddeelnemer;
use Illuminate\Database\Seeder;

class WedstrijddeelnemerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Wedstrijd::all() as $wedstrijd) {
            foreach (Deelnemer::all() as $deelnemer) {
                Wedstrijddeelnemer::factory()->create(
                    [
                        'wedstrijd_id' => $wedstrijd->id,
                        'deelnemer_id' => $deelnemer->id,
                    ]
                );
            }
        }
    }
}
