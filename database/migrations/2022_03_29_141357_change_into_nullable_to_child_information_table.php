<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIntoNullableToChildInformationTable extends Migration
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
            $table->smallInteger('type')->tinyInteger('type')->unsigned()->nullable()->change();
            $table->boolean('free_of_charge')->nullable()->change();
            $table->boolean('certificate_of_payment')->nullable()->change();
            $table->boolean('tax_exempt_household')->nullable()->change();
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
        });
    }
}
