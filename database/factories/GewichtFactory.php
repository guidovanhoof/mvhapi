<?php

namespace Database\Factories;

use App\Models\Gewicht;
use App\Models\Plaats;
use Illuminate\Database\Eloquent\Factories\Factory;

class GewichtFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gewicht::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "plaats_id" => function() {
                return Plaats::factory()->create()->id;
            },
            "gewicht" => $this->faker->numberBetween(1, 66666),
            "is_geldig" => 1,
        ];
    }
}
