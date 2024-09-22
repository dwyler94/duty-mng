<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefecationToContactBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_books', function (Blueprint $table) {
            //
            $table->time('defecation_time_1_home')->nullable();
            $table->time('defecation_time_2_home')->nullable();
            $table->time('defecation_time_3_home')->nullable();
            $table->time('defecation_time_1_school')->nullable();
            $table->time('defecation_time_2_school')->nullable();
            $table->time('defecation_time_3_school')->nullable();
            $table->string('defecation_memo_1_home', 255)->nullable();
            $table->string('defecation_memo_2_home', 255)->nullable();
            $table->string('defecation_memo_3_home', 255)->nullable();
            $table->string('defecation_memo_1_school', 255)->nullable();
            $table->string('defecation_memo_2_school', 255)->nullable();
            $table->string('defecation_memo_3_school', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_books', function (Blueprint $table) {
//            $table->dropColumn('defecation_count_1_home');
//            $table->dropColumn('defecation_count_2_home');
//            $table->dropColumn('defecation_count_1_school');
//            $table->dropColumn('defecation_count_2_school');
        });
    }
}
