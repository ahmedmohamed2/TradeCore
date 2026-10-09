<?php

use Illuminate\Support\Facades\Schema;

test('system_settings table has the expected columns', function () {
    expect(Schema::hasTable('system_settings'))->toBeTrue();

    expect(Schema::hasColumns('system_settings', [
        'id',
        'system_name',
        'system_photo',
        'active',
        'general_alert',
        'address',
        'phone',
        'created_by',
        'updated_by',
        'company_code',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});
