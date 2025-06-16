<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'last_name' => 'User',
                'document' => '12345678',
                'state' => 'activo',
                'phone' => '1234567890',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Encripta la contraseña
                'role' => 'admin',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Usuario',
                'last_name' => 'Demo',
                'document' => '87654321',
                'state' => 'activo',
                'phone' => '0987654321',
                'email' => 'pasante@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Encripta la contraseña
                'role' => 'pasante',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}