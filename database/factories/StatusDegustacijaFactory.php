<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\StatusDegustacija;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusDegustacijaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StatusDegustacija::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Naziv' => $this->faker->text(255),
        ];
    }
}
