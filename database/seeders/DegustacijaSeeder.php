<?php

namespace Database\Seeders;

use App\Models\Degustacija;
use Illuminate\Database\Seeder;

class DegustacijaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Degustacija::factory()
            ->count(5)
            ->create();
    }
}
