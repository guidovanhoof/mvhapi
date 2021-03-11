<?php

namespace Database\Seeders;

use App\Models\Wedstrijdtype;
use Illuminate\Database\Seeder;

class WedstrijdtypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Ledenwedstrijd',
            'Jeugdvissen',
            'Koppel met getrokken maat, apart vissen',
            'Criterium',
            'Zomercriterium',
            'Woensdagcriterium'
        ];

        foreach ($types as $type) {
            Wedstrijdtype::factory()->create(["omschrijving" => $type]);
        }
    }
}
