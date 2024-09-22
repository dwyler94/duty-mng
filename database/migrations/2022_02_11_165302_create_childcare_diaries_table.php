<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildcareDiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childcare_diaries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('date')->nullable();
            $table->foreignId('office_id')->constrained('offices');
            $table->foreignId('children_class_id')->constrained('children_classes');
            $table->text('weather')->nullable();
            $table->text('aim')->nullable();
            $table->text('activity_content')->nullable();
            $table->text('consideration')->nullable();
            $table->text('evaluation_reflection')->nullable();
            $table->text('health_status')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('create_user_id')->nullable();
            $table->foreignId('update_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('childcare_diaries');
    }
}
