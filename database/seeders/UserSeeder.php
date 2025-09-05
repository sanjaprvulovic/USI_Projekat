<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory()
        //     ->count(5)
        //     ->create();

        $roles = Role::pluck('id', 'Naziv');

        // Personas iz specifikacije
        $personas = [
            // KLIJENTI
            ['name' => 'Nadica', 'surname' => 'Lazić',     'email' => 'nadica.lazic@example.com',   'phone' => '+381601234001', 'role' => 'Klijent'],
            ['name' => 'Nenad',  'surname' => 'Lukić',     'email' => 'nenad.lukic@example.com',    'phone' => '+381601234002', 'role' => 'Klijent'],

            // MENADŽERI DOGADJAJA
            ['name' => 'Sandra', 'surname' => 'Lazarev',   'email' => 'sandra.lazarev@example.com', 'phone' => '+381601234003', 'role' => 'Menadžer dogadjaja'],
            ['name' => 'Dejan',  'surname' => 'Ristic',    'email' => 'dejan.ristic@example.com',   'phone' => '+381601234004', 'role' => 'Menadžer dogadjaja'],

            // ADMINISTRATORI
            ['name' => 'Sanja',  'surname' => 'Prvulović', 'email' => 'sanja.prvulovic@example.com','phone' => '+381601234005', 'role' => 'Administrator'],
            ['name' => 'Uroš',   'surname' => 'Petković',  'email' => 'uros.petkovic@example.com',  'phone' => '+381601234006', 'role' => 'Administrator'],
        ];

        foreach ($personas as $p) {
            // email je unique – koristimo firstOrCreate da izbegnemo duplikate
            User::firstOrCreate(
                ['email' => $p['email']],
                [
                    'name'     => $p['name'],
                    'surname'  => $p['surname'],
                    'phone'    => $p['phone'],
                    'password' => Hash::make('pivara123'), // default lozinka
                    'role_id'  => $roles[$p['role']] ?? null,
                ]
            );
        }
    }
}
