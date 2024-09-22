<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarkAndReasonForVacationIdToAttendancesTable extends Migration
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
            $table->foreignId('reason_for_vacation_id')->nullable()->constrained('reason_for_vacations')->nullOnDelete();
            $table->string('remark')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->foreignId('approve_user_id')->nullable()->constrained('users')->nullOnDelete();
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
            $table->dropConstrainedForeignId('reason_for_vacation_id');
            $table->dropConstrainedForeignId('approve_user_id');
            $table->dropColumn('remark');
            $table->dropColumn('approved_at');
        });
    }
}
