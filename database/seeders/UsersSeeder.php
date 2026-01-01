<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Omar Mahgoub',
            'email' => 'omar@app.com',
            'password' => bcrypt('PassWord'), // Change this to a secure password
        ]);
    }
}
