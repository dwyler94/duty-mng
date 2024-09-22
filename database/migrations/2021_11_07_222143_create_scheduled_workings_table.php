<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledWorkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_workings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('year_id');
            $table->foreignId('office_id');
            $table->tinyInteger('month', false, true);
            $table->unsignedSmallInteger('days')->nullable();
            $table->unsignedSmallInteger('hours')->nullable();
            $table->softDeletes();
            $table->foreignId('create_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();

            $table->foreign('create_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('update_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_workings');
    }
}
