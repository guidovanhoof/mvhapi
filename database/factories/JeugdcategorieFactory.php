<?php

namespace Database\Factories;

use App\Models\Jeugdcategorie;
use Illuminate\Database\Eloquent\Factories\Factory;

class JeugdcategorieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Jeugdcategorie::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'omschrijving' => 'Jeugdcategorie ' . $this->getOplopendVolgnummer(),
        ];
    }

    /**
     * Genereer uniek en oplopend volgnummer
     * @return int
     */
    private function getOplopendVolgnummer(): int
    {
        static $nummer = 1;
        return $nummer++;
    }
}
