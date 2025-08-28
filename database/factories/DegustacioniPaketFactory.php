<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\DegustacioniPaket;
use Illuminate\Database\Eloquent\Factories\Factory;

class DegustacioniPaketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DegustacioniPaket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'NazivPaketa' => $this->faker->text(255),
            'Cena' => $this->faker->randomNumber(0),
            'Opis' => $this->faker->text(),
        ];
    }
}
