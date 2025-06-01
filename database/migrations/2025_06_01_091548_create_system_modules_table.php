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
        Schema::create('system_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_module_category_id')->constrained()->onDelete('cascade');
            $table->integer('position')->nullable()->default(1);
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('has_add')->default(true);
            $table->boolean('has_show')->default(true);
            $table->boolean('has_update')->default(true);
            $table->boolean('has_destroy')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub-menus');
    }
};
