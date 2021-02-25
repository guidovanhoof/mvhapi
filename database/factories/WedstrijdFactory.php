<?php

namespace Database\Factories;

use App\Models\Kalender;
use App\Models\Wedstrijd;
use App\Models\Wedstrijdtype;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class WedstrijdFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wedstrijd::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'kalender_id' => function() {
                return Kalender::factory()->create()->id;
            },
            'datum' => $this->getVolgendeDatum(),
            'nummer' => $this->faker->numberBetween(1, 65535),
            'omschrijving' => $this->faker->sentence(),
            'sponsor' => $this->faker->sentence(),
            'aanvang' =>$this->faker->date('H:i:s'),
            'wedstrijdtype_id' => function() {
                return Wedstrijdtype::factory()->create()->id;
            },
            'opmerkingen' => $this->faker->sentences(2,true)
        ];
    }

    /**
     * Genereer een volgende en unieke datum
     *
     * @return string
     */
    private function getVolgendeDatum(): string
    {
        static $volgendeDatum = '1999-12-31';
        $volgendeDatum = Carbon::parse($volgendeDatum)->addDay()->format('Y-m-d');
        return $volgendeDatum;
    }
}
