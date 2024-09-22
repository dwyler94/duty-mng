<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeScheduledWorkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_scheduled_workings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->nullable();
            $table->foreignId('scheduled_working_id');

            $table->foreignId('create_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_scheduled_workings');
    }
}
