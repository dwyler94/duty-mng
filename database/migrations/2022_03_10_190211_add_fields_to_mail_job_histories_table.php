<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToMailJobHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_job_histories', function (Blueprint $table) {
            //
            $table->foreignId('child_id')->nullable()->constrained('children')->nullOnDelete();
            $table->foreignId('file_id_1')->nullable()->constrained('attachments')->nullOnDelete();
            $table->foreignId('file_id_2')->nullable()->constrained('attachments')->nullOnDelete();
            $table->foreignId('file_id_3')->nullable()->constrained('attachments')->nullOnDelete();
            $table->foreignId('file_id_4')->nullable()->constrained('attachments')->nullOnDelete();
            $table->foreignId('file_id_5')->nullable()->constrained('attachments')->nullOnDelete();
            $table->foreignId('file_id_6')->nullable()->constrained('attachments')->nullOnDelete();
            $table->foreignId('file_id_7')->nullable()->constrained('attachments')->nullOnDelete();
            $table->foreignId('file_id_8')->nullable()->constrained('attachments')->nullOnDelete();
            $table->foreignId('file_id_9')->nullable()->constrained('attachments')->nullOnDelete();
            $table->foreignId('file_id_10')->nullable()->constrained('attachments')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail_job_histories', function (Blueprint $table) {
            //
        });
    }
}
