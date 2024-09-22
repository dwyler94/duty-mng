<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('number', 20);
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 100);

            $table->foreignId('office_id')->nullable();
            $table->foreignId('role_id')->nullable();
            $table->foreignId('employment_status_id')->nullable();
            $table->boolean('enrolled')->default(1);
            $table->string('qr', 100)->nullable();
            $table->softDeletes();
            $table->unsignedTinyInteger('working_hours')->nullable();

            $table->foreignId('create_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
