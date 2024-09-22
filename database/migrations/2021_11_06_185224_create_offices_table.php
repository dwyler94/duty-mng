<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->nullable();
            $table->foreignId('industry_group_id')->nullable();
            $table->foreignId('office_group_id')->nullable();
            $table->foreignId('rest_deduction_id');
            $table->string('name', 50);
            $table->string('number', 20)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('tel', 20)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreignId('create_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();

            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('industry_group_id')->references('id')->on('industry_groups');
            $table->foreign('office_group_id')->references('id')->on('office_groups');
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
        Schema::dropIfExists('offices');
    }
}
