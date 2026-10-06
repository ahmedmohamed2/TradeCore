<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\PermissionCatalog;
use App\Support\RoleName;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionCatalog::class)->sync();

        $user = User::query()->firstOrCreate(
            ['email' => 'super_admin@app.com'],
            [
                'name' => 'Super Admin',
                'password' => '123456789',
                'profile_photo_path' => 'default.png',
            ],
        );

        $user->assignRole(RoleName::SuperAdmin);
    }
}
