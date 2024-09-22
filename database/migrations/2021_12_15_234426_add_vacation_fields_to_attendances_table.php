<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVacationFieldsToAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
            $table->float('annual_paid_time', 6, 2)->nullable();
            $table->float('special_paid_time', 6, 2)->nullable();
            $table->float('special_unpaid_time', 6, 2)->nullable();
            $table->float('other_unpaid_time', 6, 2)->nullable();
            $table->float('substitute_time', 6,2)->nullable();
            $table->integer('substitute_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
            $table->dropColumn('annual_paid_time');
            $table->dropColumn('special_paid_time');
            $table->dropColumn('special_unpaid_time');
            $table->dropColumn('other_unpaid_time');
            $table->dropColumn('substitute_time');
            $table->dropColumn('substitute_day');
        });
    }
}
