<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('child_id');
            $table->string('message', 200);
            $table->string('url', 200)->nullable();
            $table->boolean('browsing_flag')->default(0);
            $table->dateTime('browsing_datetime')->nullable();
            $table->integer('browsing_user_id')->nullable();
            $table->boolean('process_flag')->default(0);
            $table->dateTime('process_datetime')->nullable();
            $table->integer('process_user_id')->nullable();
            $table->foreignId('create_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();
            $table->timestamps();
            $table->foreign('create_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('update_user_id')->references('id')->on('users')->onDelete('set null');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
