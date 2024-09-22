<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildrenAttendencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('children_attendences', function (Blueprint $table) {
            $table->id();
            $table->dateTime('commuting_time')->nullable();
            $table->dateTime('leave_time')->nullable();
            $table->boolean('absence')->default(0);
            $table->foreignId('reason_for_absence_id')->nullable()->constrained('reason_for_absences')->nullOnDelete();
            $table->smallInteger('behind_time')->nullable();
            $table->smallInteger('leave_early')->nullable();
            $table->time('extension')->nullable();
            $table->date('date')->nullable();
            $table->unsignedTinyInteger('month')->nullable();
            $table->unsignedTinyInteger('day')->nullable();

            $table->foreignId('year_id')->nullable()->constrained('years')->nullOnDelete();
            $table->foreignId('child_id')->constrained('children');

            $table->foreignId('create_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('children_attendences');
    }
}
