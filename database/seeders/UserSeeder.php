<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Nadja',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin123'),
                'role_id' => 1, 
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        for ($i = 0; $i < 20; $i++) {
            DB::table('users')->insert([
                'name' => 'User' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => bcrypt('password' . $i),
                'role_id' => 3,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
