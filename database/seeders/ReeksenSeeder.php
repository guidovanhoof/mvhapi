<?php

namespace Database\Seeders;

use App\Models\Reeks;
use Illuminate\Database\Seeder;

class ReeksenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Reeks::all() as $wedstrijd) {
            foreach (range(1, random_int(1, 3)) as $nummer) {
                Reeks::factory()->create(
                    [
                        'wedstrijd_id' => $wedstrijd->id,
                        'nummer' => $nummer,
                    ]
                );
            }
        }
    }
}
