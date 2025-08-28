<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\DegustacijaPaket;
use Illuminate\Database\Eloquent\Factories\Factory;

class DegustacijaPaketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DegustacijaPaket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'DegustacijaID' => $this->faker->randomNumber(),
            'degustacija_id' => \App\Models\Degustacija::factory(),
            'degustacioni_paket_id' => \App\Models\DegustacioniPaket::factory(),
        ];
    }
}
