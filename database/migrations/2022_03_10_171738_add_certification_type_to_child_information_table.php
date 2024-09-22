<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCertificationTypeToChildInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('child_information', function (Blueprint $table) {
            //
            $table->unsignedTinyInteger('certification_type')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('child_information', function (Blueprint $table) {
            //
            $table->dropColumn('certification_type');
        });
    }
}
