<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShiftsToAttendancesTable extends Migration
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
            $table->foreignId('shift_1_id')->nullable()->constrained('shift_plans')->nullOnDelete();
            $table->foreignId('shift_2_id')->nullable()->constrained('shift_plans')->nullOnDelete();
            $table->foreignId('shift_3_id')->nullable()->constrained('shift_plans')->nullOnDelete();
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
        });
    }
}
