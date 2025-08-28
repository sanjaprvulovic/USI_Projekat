<?php

namespace Database\Factories;

use App\Models\PrIjava;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrIjavaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrIjava::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Datum' => $this->faker->dateTime(),
            'status_prijava_id' => \App\Models\StatusPrijava::factory(),
            'degustacija_id' => \App\Models\Degustacija::factory(),
            'user_id' => \App\Models\User::factory(),
            'degustacioni_paket_id' => \App\Models\DegustacioniPaket::factory(),
        ];
    }
}
