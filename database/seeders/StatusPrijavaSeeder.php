<?php

namespace Database\Seeders;

use App\Models\StatusPrijava;
use Illuminate\Database\Seeder;

class StatusPrijavaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusPrijava::factory()
            ->count(5)
            ->create();
    }
}
