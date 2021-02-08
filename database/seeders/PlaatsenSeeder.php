<?php

namespace Database\Seeders;

use App\Models\Plaats;
use App\Models\Reeks;
use Illuminate\Database\Seeder;

class PlaatsenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Reeks::all() as $reeks) {
            foreach (range(1, random_int(1, 10)) as $nummer) {
                Plaats::factory()->create(
                    [
                        'reeks_id' => $reeks->id,
                        'nummer' => $nummer,
                    ]
                );
            }
        }
    }
}
