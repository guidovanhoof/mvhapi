<?php

namespace Database\Seeders;

use App\Models\Jeugdcategorie;
use App\Models\Wedstrijd;
use App\Models\WedstrijddeelnemerJeugdcategorie;
use App\Models\Wedstrijdtype;
use Illuminate\Database\Seeder;

class WedstrijddeelnemerJeugdcategorieenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wedstrijdtype = Wedstrijdtype::where('omschrijving', strtoupper('jeugdvissen'))->first();
        $jeugdcategorie = Jeugdcategorie::first();
        foreach (Wedstrijd::where('wedstrijdtype_id', $wedstrijdtype->id)->get() as $wedstrijd) {
            foreach ($wedstrijd->deelnemers as $deelnemer) {
                WedstrijddeelnemerJeugdcategorie::factory()->create(
                    [
                        'wedstrijddeelnemer_id' => $deelnemer->id,
                        'jeugdcategorie_id' => $jeugdcategorie->id,
                    ]
                );
            }
        }
    }
}
