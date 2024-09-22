<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHourlyWagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hourly_wages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('office_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('hourly_wages');
    }
}
