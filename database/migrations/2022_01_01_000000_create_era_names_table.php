<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEraNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('era_names', function (Blueprint $table) {
            $table->id()->comment('和暦ID');
            $table->string('name')->comment('名称');
            $table->string('short_name')->comment('略称');
            $table->date('start_date')->comment('開始日');
            $table->date('end_date')->comment('終了日');
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
        Schema::dropIfExists('era_names');
    }
}
