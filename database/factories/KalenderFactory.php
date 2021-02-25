<?php

namespace Database\Factories;

use App\Models\Kalender;
use Illuminate\Database\Eloquent\Factories\Factory;

class KalenderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kalender::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'jaar' => $this->getVolgendeJaar(),
            'opmerkingen' => $this->faker->sentence()
        ];
    }

    /**
     * Genereer een volgend en uniek jaar
     *
     * @return int
     */
    private function getVolgendeJaar(): int
    {
        static $volgendJaar = 2000;
        $volgendJaar = $volgendJaar == 2101 ? 2000 : $volgendJaar;
        return $volgendJaar++;
    }
}
