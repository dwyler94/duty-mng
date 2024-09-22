<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMailJobHistoryIdToMailHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_histories', function (Blueprint $table) {
            //
            $table->foreignId('mail_job_history_id')->nullable()->constrained('mail_job_histories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail_histories', function (Blueprint $table) {
            //
        });
    }
}
