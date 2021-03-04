<?php

namespace Database\Factories;

use App\Models\Jeugdcategorie;
use App\Models\Wedstrijddeelnemer;
use App\Models\WedstrijddeelnemerJeugdcategorie;
use Illuminate\Database\Eloquent\Factories\Factory;

class WedstrijddeelnemerJeugdcategorieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WedstrijddeelnemerJeugdcategorie::class;

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
            'jeugdcategorie_id' => function () {
                return Jeugdcategorie::factory()->create()->id;
            },
        ];
    }
}
