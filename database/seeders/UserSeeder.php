<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@app.com',
            'password' => bcrypt('123456789'),
            'profile_photo_path' => 'default.png',

        ]);
    }
}
