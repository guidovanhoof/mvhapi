<?php

namespace Database\Seeders;

use App\Models\Jeugdcategorie;
use Illuminate\Database\Seeder;

class JeugdcategorieenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorieen = [
            '6 - 8 jaar',
            '9 - 11 jaar',
            '12 - 14 jaar',
            '15 - 16 jaar',
        ];

        foreach ($categorieen as $categorie) {
            Jeugdcategorie::factory()->create(
                ['omschrijving' => $categorie]
            );
        }
    }
}
