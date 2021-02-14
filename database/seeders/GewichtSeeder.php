<?php

namespace Database\Seeders;

use App\Models\Gewicht;
use App\Models\Plaats;
use Illuminate\Database\Seeder;

class GewichtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Plaats::all() as $plaats) {
            foreach (range(1, random_int(1, 3)) as $nummer) {
                Gewicht::factory()->create(
                    [
                        'plaats_id' => $plaats->id,
                        'gewicht' => random_int(1, 26969),
                        'is_geldig' => true,
                    ]
                );
            }
        }
    }
}
