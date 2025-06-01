<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
                        $table->foreignId('room_id')->constrained('rooms');
            $table->string('sensor_name');
            $table->string('sensor_type');
            $table->date('installation_date');
            $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('sensors');
    }
};
