<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('temperature_readings', function (Blueprint $table) {
            $table->id();
                        $table->foreignId('sensor_id')->constrained('sensors');
            $table->decimal('temperature_value');
            $table->timestamp('recorded_at');

            $table->foreignId("created_by")->constrained("users");
            $table->foreignId("deleted_by")->nullable()->constrained("users");
            $table->longText("delete_comment")->nullable();
            $table->foreignId("updated_by")->nullable()->constrained("users");

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('temperature_readings');
    }
};
