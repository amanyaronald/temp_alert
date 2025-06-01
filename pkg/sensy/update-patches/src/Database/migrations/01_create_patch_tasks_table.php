<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patch_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patch_id')->constrained();
            $table->string('function');
            $table->string('description');

            $table->enum('status', ['pending', 'applied', 'failed'])->default('pending');
            $table->bigInteger('user_id')->nullable();
            $table->timestamp('applied_at')->nullable();

            $table->unique(['patch_id', 'function'], 'patch_id_function_unique');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patch_tasks');
    }
};
