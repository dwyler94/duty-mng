<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildcarePlanDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childcare_plan_days', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('date')->nullable();
            $table->foreignId('child_id')->constrained('children');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('absent')->default(0);

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
        Schema::dropIfExists('childcare_plan_days');
    }
}
