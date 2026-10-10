<?php

use App\Http\Controllers\TreasuryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function (): void {
    Route::resource('treasuries', TreasuryController::class)->except(['show']);
});
