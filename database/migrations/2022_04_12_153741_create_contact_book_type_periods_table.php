<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactBookTypePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_book_type_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id');
            $table->unsignedTinyInteger('type');
            $table->date('start_date');
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
        Schema::dropIfExists('contact_book_type_periods');
    }
}
