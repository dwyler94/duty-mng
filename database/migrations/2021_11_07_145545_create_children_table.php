<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('number', 20);
            $table->string('password', 255);
            $table->string('email', 50)->nullable();
            $table->foreignId('office_id')->nullable();
            $table->tinyInteger('enrollment_class', false, true)->nullable();
            $table->string('qr', 100)->nullable();
            $table->softDeletes();
            $table->foreignId('create_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('children');
    }
}
