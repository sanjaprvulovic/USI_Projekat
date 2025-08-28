<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DegustacioniPaket;

class DegustacioniPaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DegustacioniPaket::factory()
            ->count(5)
            ->create();
    }
}
