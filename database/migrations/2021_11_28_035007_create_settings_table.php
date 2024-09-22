<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedTinyInteger('fraction_commuting_time')->nullable();
            $table->unsignedTinyInteger('fraction_leave_time')->nullable();

            $table->unsignedTinyInteger('fraction_behind_time')->nullable();
            $table->unsignedTinyInteger('fraction_leave_early')->nullable();
            $table->unsignedTinyInteger('round_up_commuting_time')->nullable();
            $table->unsignedTinyInteger('truncate_leave_time')->nullable();
            $table->unsignedTinyInteger('round_up_behind_time')->nullable();
            $table->unsignedTinyInteger('truncate_leave_early')->nullable();
            $table->unsignedTinyInteger('consecutive_work')->nullable();

            $table->softDeletes();

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
        Schema::dropIfExists('settings');
    }
}
