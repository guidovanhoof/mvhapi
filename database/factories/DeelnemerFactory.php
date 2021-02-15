<?php

namespace Database\Factories;

use App\Models\Deelnemer;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeelnemerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Deelnemer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "nummer" => $this->faker->numberBetween(1, 65535),
            "naam" => $this->faker->name
        ];
    }
}
