<?php

namespace Database\Factories;

use App\Models\Plaats;
use App\Models\Plaatsdeelnemer;
use App\Models\Wedstrijddeelnemer;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaatsdeelnemerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plaatsdeelnemer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'plaats_id' => function () {
                return Plaats::factory()->create()->id;
            },
            'wedstrijddeelnemer_id' => function () {
                return Wedstrijddeelnemer::factory()->create()->id;
            },
            'is_weger' => 0,
        ];
    }
}
