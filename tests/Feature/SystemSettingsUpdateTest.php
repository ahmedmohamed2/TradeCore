<?php

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('guests are redirected to the login page from the edit form', function () {
    $setting = SystemSetting::factory()->create();

    $this->get(route('system-settings.edit', $setting))
        ->assertRedirect(route('login'));
});

test('guests cannot update system settings', function () {
    $setting = SystemSetting::factory()->create([
        'system_name' => 'Original Name',
    ]);

    $this->put(route('system-settings.update', $setting), validSystemSettingPayload())
        ->assertRedirect(route('login'));

    expect($setting->fresh()->system_name)->toBe('Original Name');
});

test('authenticated users can view the edit form', function () {
    $user = User::factory()->create();
    $setting = SystemSetting::factory()->create([
        'system_name' => 'TradeCore',
    ]);

    $this->actingAs($user)
        ->get(route('system-settings.edit', $setting))
        ->assertOk()
        ->assertSee('TradeCore')
        ->assertSee('Save Changes');
});

test('authenticated users can update system settings', function () {
    $user = User::factory()->create();
    $setting = SystemSetting::factory()->create([
        'system_name' => 'Original Name',
        'company_code' => 'OLD001',
        'updated_by' => User::factory(),
    ]);

    $this->actingAs($user)
        ->from(route('system-settings.edit', $setting))
        ->put(route('system-settings.update', $setting), validSystemSettingPayload([
            'system_name' => 'Updated Name',
            'company_code' => 'NEW001',
            'address' => 'Cairo',
            'phone' => '01000000000',
            'general_alert' => 'Maintenance tonight',
        ]))
        ->assertRedirect(route('system-settings.index'))
        ->assertSessionHas('status');

    $setting->refresh();

    expect($setting)
        ->system_name->toBe('Updated Name')
        ->company_code->toBe('NEW001')
        ->address->toBe('Cairo')
        ->phone->toBe('01000000000')
        ->general_alert->toBe('Maintenance tonight')
        ->active->toBeTrue()
        ->updated_by->toBe($user->id);
});

test('system settings cannot be updated with invalid data', function () {
    $user = User::factory()->create();
    $setting = SystemSetting::factory()->create([
        'system_name' => 'Original Name',
    ]);

    $this->actingAs($user)
        ->from(route('system-settings.edit', $setting))
        ->put(route('system-settings.update', $setting), validSystemSettingPayload([
            'system_name' => '',
            'company_code' => '',
        ]))
        ->assertRedirect(route('system-settings.edit', $setting))
        ->assertSessionHasErrors(['system_name', 'company_code']);

    expect($setting->fresh()->system_name)->toBe('Original Name');
});

test('updating system settings stores a photo in company photos uploads', function () {
    Storage::fake('company_photos');

    $user = User::factory()->create();
    $setting = SystemSetting::factory()->create([
        'system_photo' => null,
    ]);

    $photo = UploadedFile::fake()->image('logo.png');

    $this->actingAs($user)
        ->put(route('system-settings.update', $setting), validSystemSettingPayload([
            'system_photo' => $photo,
        ]))
        ->assertRedirect(route('system-settings.index'));

    $setting->refresh();

    expect($setting->system_photo)->not->toBeNull()
        ->and($setting->system_photo)->not->toBe('0')
        ->and($setting->system_photo)->not->toBe(0);

    Storage::disk('company_photos')->assertExists($setting->system_photo);
});

test('replacing a system photo deletes the previous file', function () {
    Storage::fake('company_photos');

    $user = User::factory()->create();
    $oldPhoto = UploadedFile::fake()->image('old.png');
    $oldPath = $oldPhoto->store('', 'company_photos');

    $setting = SystemSetting::factory()->create([
        'system_photo' => $oldPath,
    ]);

    $this->actingAs($user)
        ->put(route('system-settings.update', $setting), validSystemSettingPayload([
            'system_photo' => UploadedFile::fake()->image('new.png'),
        ]))
        ->assertRedirect(route('system-settings.index'));

    $setting->refresh();

    Storage::disk('company_photos')->assertMissing($oldPath);
    Storage::disk('company_photos')->assertExists($setting->system_photo);
});

test('omitting a photo keeps the existing system photo', function () {
    Storage::fake('company_photos');

    $user = User::factory()->create();
    $photo = UploadedFile::fake()->image('logo.png');
    $path = $photo->store('', 'company_photos');

    $setting = SystemSetting::factory()->create([
        'system_photo' => $path,
    ]);

    $this->actingAs($user)
        ->put(route('system-settings.update', $setting), validSystemSettingPayload())
        ->assertRedirect(route('system-settings.index'));

    expect($setting->fresh()->system_photo)->toBe($path);
    Storage::disk('company_photos')->assertExists($path);
});

test('created_by cannot be changed through the update form', function () {
    $creator = User::factory()->create();
    $user = User::factory()->create();
    $setting = SystemSetting::factory()->create([
        'created_by' => $creator->id,
    ]);

    $this->actingAs($user)
        ->put(route('system-settings.update', $setting), validSystemSettingPayload([
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]))
        ->assertRedirect(route('system-settings.index'));

    expect($setting->fresh()->created_by)->toBe($creator->id);
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function validSystemSettingPayload(array $overrides = []): array
{
    return [
        'system_name' => 'TradeCore',
        'company_code' => 'TC001',
        'address' => 'Cairo',
        'phone' => '01000000000',
        'general_alert' => 'All systems operational',
        'active' => '1',
        ...$overrides,
    ];
}
