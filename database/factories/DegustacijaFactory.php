<?php

namespace Database\Factories;

use App\Models\Degustacija;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class DegustacijaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Degustacija::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Naziv' => $this->faker->text(255),
            'Datum' => $this->faker->dateTime(),
            'Lokacija' => $this->faker->text(255),
            'Kapacitet' => $this->faker->randomNumber(0),
            'user_id' => \App\Models\User::factory(),
            'status_degustacija_id' => \App\Models\StatusDegustacija::factory(),
        ];
    }
}
