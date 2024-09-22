<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildcarePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childcare_plans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('day_of_weeks')->nullable();
            $table->foreignId('child_id')->constrained('children');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('excluding_holidays')->default(0);

            $table->foreignId('create_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('childcare_plans');
    }
}
