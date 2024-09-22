<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAbsentToChildcarePlanDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('childcare_plan_days', function (Blueprint $table) {
            //
            $table->dropColumn('absent');
            $table->foreignId('absent_id')->nullable()->constrained('reason_for_absences')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('childcare_plan_days', function (Blueprint $table) {
            //
        });
    }
}
