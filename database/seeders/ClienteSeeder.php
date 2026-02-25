<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    User::create([
        'name' => 'Cliente Demo',
        'email' => 'cliente@demo.com',
        'password' => Hash::make('password123'),
        'role' => 'cliente', // si tienes columna role
    ]);
}
}
