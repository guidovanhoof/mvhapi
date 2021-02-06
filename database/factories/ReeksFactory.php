<?php

namespace Database\Factories;

use App\Models\Reeks;
use App\Models\Wedstrijd;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReeksFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reeks::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'wedstrijd_id' => function() {
                return Wedstrijd::factory()->create()->id;
            },
            'nummer' => $this->faker->numberBetween(1, 255),
            'aanvang' => $this->faker->time(),
            'duur' => $this->faker->time(),
            'gewicht_zak' => $this->faker->numberBetween(0, 255),
            'opmerkingen' => $this->faker->text(75),
        ];
    }
}
