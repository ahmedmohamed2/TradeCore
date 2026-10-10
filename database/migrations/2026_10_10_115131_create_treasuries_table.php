<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('treasuries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('name')->unique();
            $table->boolean('is_master')->default(false)->index();
            $table->unsignedTinyInteger('master_marker')->nullable()->unique();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->unsignedInteger('last_payment_number')->default(0);
            $table->unsignedInteger('last_collection_number')->default(0);
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treasuries');
    }
};
