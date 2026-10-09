<?php

use App\Http\Controllers\SystemSettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'),
])->group(function (): void {
    Route::resource('system-settings', SystemSettingController::class)->only(['index', 'edit', 'update']);
});
