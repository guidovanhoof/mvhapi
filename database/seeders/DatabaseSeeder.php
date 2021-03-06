<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                UsersSeeder::class,
                KalendersSeeder::class,
                WedstrijdtypesSeeder::class,
                WedstrijdenSeeder::class,
                ReeksenSeeder::class,
                PlaatsenSeeder::class,
                GewichtSeeder::class,
                DeelnemersSeeder::class,
                WedstrijddeelnemerSeeder::class,
                PlaatsdeelnemerSeeder::class,
                JeugdcategorieenSeeder::class,
                WedstrijddeelnemerJeugdcategorieenSeeder::class,
                GetrokkenMatenSeeder::class,
            ]
        );
    }
}
