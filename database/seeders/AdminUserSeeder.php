<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'nom' => 'Admin',
            'prenom' => 'User',
            'pays' => 'Burkina Faso',
            'phone' => '00000000',
            'email' => 'admin@wilink-ticket.com',
            'password' => bcrypt('admin'), // You should change this in production
            'is_admin' => true,
        ]);
    }
}
