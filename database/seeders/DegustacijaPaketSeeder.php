<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DegustacijaPaket;

class DegustacijaPaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DegustacijaPaket::factory()
            ->count(5)
            ->create();
    }
}
