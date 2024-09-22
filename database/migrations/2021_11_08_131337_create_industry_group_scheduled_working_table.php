<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndustryGroupScheduledWorkingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industry_group_scheduled_working', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('industry_group_id');
            $table->foreignId('scheduled_working_id');
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
        Schema::dropIfExists('industry_group_scheduled_working');
    }
}
