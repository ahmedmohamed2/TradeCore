<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->where('email', 'super_admin@app.com')->first();

        SystemSetting::query()->create([
            'system_name' => 'TradeCore',
            'system_photo' => null,
            'active' => true,
            'general_alert' => null,
            'address' => null,
            'phone' => null,
            'created_by' => $admin?->id,
            'updated_by' => $admin?->id,
            'company_code' => 'TC001',
        ]);
    }
}
