<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSystemSettingRequest;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SystemSettingController extends Controller
{
    /**
     * Display the active system settings.
     */
    public function index(): View
    {
        $systemSettings = SystemSetting::query()
            ->where('active', true)
            ->with(['createdBy', 'updatedBy'])
            ->first();

        return view('system-settings.index', compact('systemSettings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SystemSetting $systemSetting): View
    {
        return view('system-settings.edit', compact('systemSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSystemSettingRequest $request, SystemSetting $systemSetting): RedirectResponse
    {
        $attributes = $request->safe()->only([
            'system_name',
            'active',
            'general_alert',
            'address',
            'phone',
            'company_code',
        ]);
        $attributes['updated_by'] = $request->user()->id;

        $photo = $request->file('system_photo');

        if ($photo instanceof UploadedFile) {
            File::ensureDirectoryExists(public_path('uploads/company_photos'));

            $filename = $photo->hashName();
            Storage::disk('company_photos')->putFileAs('', $photo, $filename);

            if (filled($systemSetting->system_photo)) {
                Storage::disk('company_photos')->delete($systemSetting->system_photo);
            }

            $attributes['system_photo'] = $filename;
        }

        $systemSetting->update($attributes);

        return to_route('system-settings.index')
            ->with('status', __('System settings updated successfully.'));
    }
}
