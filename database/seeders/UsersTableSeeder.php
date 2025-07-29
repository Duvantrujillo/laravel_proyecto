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
                'name' => 'Monica',
                'last_name' => 'Triana',
                'document' => '1079174059',
                'state' => 'activo',
                'phone' => '3228662534',
                'email' => 'monica.triana059@senaacuicultura.com',
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