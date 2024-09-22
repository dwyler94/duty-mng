<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id');
            $table->foreignId('office_id')->nullable();
            $table->string('qr', 100);
            $table->unsignedInteger('ymd');
            $table->unsignedSmallInteger('counter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qr_transactions');
    }
}
