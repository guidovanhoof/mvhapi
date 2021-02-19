<?php

namespace Database\Factories;

use App\Models\Deelnemer;
use App\Models\Wedstrijd;
use App\Models\Wedstrijddeelnemer;
use Illuminate\Database\Eloquent\Factories\Factory;

class WedstrijddeelnemerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wedstrijddeelnemer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'wedstrijd_id' => function() {
                return Wedstrijd::factory()->create()->id;
            },
            'deelnemer_id' => function() {
                return Deelnemer::factory()->create()->id;
            },
            'is_gediskwalificeerd' => false,
            'opmerkingen' => null,
        ];
    }
}
