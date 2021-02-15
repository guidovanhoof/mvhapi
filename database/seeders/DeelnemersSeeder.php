<?php

namespace Database\Seeders;

use App\Models\Deelnemer;
use Illuminate\Database\Seeder;

class DeelnemersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, random_int(1, 20)) as $nummer) {
            Deelnemer::factory()->create(
                [
                    'nummer' => $nummer,
                ]
            );
        }
    }
}
