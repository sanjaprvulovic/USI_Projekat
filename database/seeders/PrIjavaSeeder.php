<?php

namespace Database\Seeders;

use App\Models\PrIjava;
use Illuminate\Database\Seeder;

class PrIjavaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrIjava::factory()
            ->count(5)
            ->create();
    }
}
