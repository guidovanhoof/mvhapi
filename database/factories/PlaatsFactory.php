<?php

namespace Database\Factories;

use App\Models\Plaats;
use App\Models\Reeks;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaatsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plaats::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "reeks_id" => function() {
                return Reeks::factory()->create()->id;
            },
            "nummer" => $this->faker->numberBetween(1, 255),
            "opmerkingen" => $this->faker->sentence,
        ];
    }
}
