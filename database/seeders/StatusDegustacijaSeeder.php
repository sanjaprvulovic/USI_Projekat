<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusDegustacija;

class StatusDegustacijaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusDegustacija::factory()
            ->count(5)
            ->create();
    }
}
