<?php

namespace Database\Factories;

use App\Models\Wedstrijdtype;
use Illuminate\Database\Eloquent\Factories\Factory;

class WedstrijdtypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wedstrijdtype::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "omschrijving" => $this->faker->word . (int) microtime(true),
        ];
    }
}
