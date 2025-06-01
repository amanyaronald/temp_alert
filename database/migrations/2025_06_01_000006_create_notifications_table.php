<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
                        $table->foreignId('user_id')->constrained('users');
            $table->foreignId('room_id')->constrained('rooms');
            $table->text('message');
            $table->string('notification_type');
            $table->timestamp('sent_at');
            $table->string('status');

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
        Schema::dropIfExists('notifications');
    }
};
