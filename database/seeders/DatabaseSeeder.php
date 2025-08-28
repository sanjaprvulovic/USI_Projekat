<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Adding an admin user
        $user = \App\Models\User::factory()
            ->count(1)
            ->create([
                'email' => 'admin@admin.com',
                'password' => \Hash::make('admin'),
            ]);

        $this->call(DegustacijaSeeder::class);
        $this->call(DegustacijaPaketSeeder::class);
        $this->call(DegustacioniPaketSeeder::class);
        $this->call(PrIjavaSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(StatusDegustacijaSeeder::class);
        $this->call(StatusPrijavaSeeder::class);
        $this->call(UserSeeder::class);
    }
}
