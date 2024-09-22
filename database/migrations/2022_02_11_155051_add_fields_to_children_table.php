<?php

use App\Models\Child;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('children', function (Blueprint $table) {
            //
            $table->unsignedTinyInteger('gender')->default(Child::GENDER_MALE);
            $table->date('birthday')->nullable();
            $table->foreignId('class_id')->nullable()->constrained('children_classes')->nullOnDelete();
            $table->date('admission_date')->nullable();
            $table->date('exit_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('children', function (Blueprint $table) {
            //
        });
    }
}
