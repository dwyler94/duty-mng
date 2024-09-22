<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_totals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');
            $table->foreignId('year_id');
            $table->unsignedTinyInteger('month');
            $table->float('working_days')->nullable();
            $table->float('total_working_hours')->nullable();
            $table->float('total_rest_hours')->nullable();
            $table->float('actual_working_hours_weekdays')->nullable();
            $table->float('actual_working_hours_saturday')->nullable();
            $table->float('scheduled_working_hours_a')->nullable();
            $table->float('scheduled_working_hours_b')->nullable();
            $table->float('excess_and_deficiency_time')->nullable();
            $table->float('overtime_hours_weekdays')->nullable();
            $table->float('overtime_hours_saturday')->nullable();
            $table->float('overtime_hours_statutory')->nullable();
            $table->float('overtime_hours_non_statutory')->nullable();
            $table->float('midnight_overtime')->nullable();
            $table->float('behind_time')->nullable();
            $table->float('leave_early')->nullable();
            $table->float('off_shift_working_hours')->nullable();
            $table->float('substitute_holiday_time')->nullable();
            $table->float('consecutive_working_hours')->nullable();
            $table->float('annual_paid_time')->nullable();
            $table->float('annual_paid_days')->nullable();
            $table->float('special_paid_time')->nullable();
            $table->float('special_paid_days')->nullable();
            $table->float('special_unpaid_time')->nullable();
            $table->float('special_unpaid_days')->nullable();
            $table->float('other_unpaid_time')->nullable();
            $table->float('other_unpaid_days')->nullable();
            $table->float('absence_days')->nullable();

            $table->foreignId('create_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();

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
        Schema::dropIfExists('attendance_totals');
    }
}
