<?php

namespace Database\Factories;

use App\Models\GetrokkenMaat;
use App\Models\Wedstrijddeelnemer;
use Illuminate\Database\Eloquent\Factories\Factory;

class GetrokkenMaatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GetrokkenMaat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'wedstrijddeelnemer_id' => function () {
                return Wedstrijddeelnemer::factory()->create()->id;
            },
            'getrokken_maat_id' => function () {
                return Wedstrijddeelnemer::factory()->create()->id;
            },
        ];
    }
}
