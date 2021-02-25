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
    public function definition(): array
    {
        return [
            "omschrijving" => 'Wedstrijdype ' . $this->getVolgnummer(),
        ];
    }

    /**
     * Genereer oplopend en uniek volgnummer
     * @return int
     */
    private function getVolgnummer(): int
    {
        static $volgnummer = 1;
        return $volgnummer++;
    }
}
