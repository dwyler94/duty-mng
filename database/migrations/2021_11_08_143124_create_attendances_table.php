<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     *
     *
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');

            $table->dateTime('commuting_time_1')->nullable();
            $table->dateTime('leave_time_1')->nullable();
            $table->float('behind_time_1', 6, 2)->nullable();
            $table->float('leave_early_1', 6, 2)->nullable();

            $table->dateTime('commuting_time_2')->nullable();
            $table->float('behind_time_2', 6, 2)->nullable();
            $table->dateTime('leave_time_2')->nullable();
            $table->float('leave_early_2', 6, 2)->nullable();

            $table->dateTime('commuting_time_3')->nullable();
            $table->float('behind_time_3', 6, 2)->nullable();
            $table->dateTime('leave_time_3')->nullable();
            $table->float('leave_early_3', 6, 2)->nullable();

            $table->foreignId('year_id')->nullable();
            $table->unsignedTinyInteger('month')->nullable();
            $table->unsignedTinyInteger('day')->nullable();

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
        Schema::dropIfExists('attendances');
    }
}
