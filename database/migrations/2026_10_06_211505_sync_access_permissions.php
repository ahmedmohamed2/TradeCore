<?php

use App\Support\PermissionCatalog;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app(PermissionCatalog::class)->sync();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
