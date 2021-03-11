<?php

namespace Database\Seeders;

use App\Models\GetrokkenMaat;
use App\Models\Wedstrijd;
use App\Models\Wedstrijdtype;
use Illuminate\Database\Seeder;

class GetrokkenMatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wedstrijddeelnemer_id = null;
        $getrokken_maat_id = null;
        $wedstrijdtype = Wedstrijdtype::where(
            'omschrijving', strtoupper('Koppel met getrokken maat, apart vissen')
        )->first();
        foreach (Wedstrijd::where('wedstrijdtype_id', $wedstrijdtype->id)->get() as $wedstrijd) {
            $deelnemers = $wedstrijd->deelnemers;
            $aantalDeelnemers = $deelnemers->count();
            $max = $aantalDeelnemers % 2 == 0 ? $aantalDeelnemers : $aantalDeelnemers - 1;
            $teller = 1;
            foreach ($deelnemers as $deelnemer) {
                if ($teller <= $max) {
                    if ($teller % 2 == 1) {
                        $wedstrijddeelnemer_id = $deelnemer->id;
                    }
                    if ($teller % 2 == 0) {
                        $getrokken_maat_id = $deelnemer->id;
                        GetrokkenMaat::factory()->create(
                            [
                                'wedstrijddeelnemer_id' => $wedstrijddeelnemer_id,
                                'getrokken_maat_id' => $getrokken_maat_id,
                            ]
                        );
                    }
                }
                $teller++;
            }
        }
    }
}
