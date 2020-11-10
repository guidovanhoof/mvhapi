<?php

namespace Database\Factories;

use App\Models\Kalender;
use Illuminate\Database\Eloquent\Factories\Factory;

class KalenderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kalender::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'jaar' => $this->faker->year,
            'opmerkingen' => $this->faker->sentence()
        ];
    }
}
