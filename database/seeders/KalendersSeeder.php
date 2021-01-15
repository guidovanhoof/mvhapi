<?php

namespace Database\Seeders;

use App\Models\Kalender;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KalendersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range($this->vijfJaarTerug(), $this->vijfJaarVerder()) as $jaar) {
            Kalender::factory()
                ->create(["jaar" => $jaar]);
        }
    }

    private function vijfJaarTerug() {
        return $this->telJaarBij(-5);
    }

    private function vijfJaarVerder() {
        return $this->telJaarBij(5);
    }

    private function telJaarBij($aantalJaren) {
        return Carbon::now()->addYear($aantalJaren)->year;
    }
}
