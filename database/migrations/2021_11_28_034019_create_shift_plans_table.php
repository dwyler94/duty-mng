<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_plans', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedTinyInteger('day_of_week');
            $table->foreignId('user_id');
            $table->foreignId('working_hours_id')->nullable();

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
        Schema::dropIfExists('shift_plans');
    }
}
