<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Role::create(['Naziv' => 'Administrator']);
       Role::create(['Naziv' => 'Menadžer dogadjaja']);
       Role::create(['Naziv' => 'Klijent']);
    }
}
