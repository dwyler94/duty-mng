<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_information', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('name');
            $table->unsignedTinyInteger('type')->default(0);
            $table->string('company_name')->nullable();
            $table->boolean('free_of_charge')->default(0);
            $table->boolean('certificate_of_payment')->default(0);
            $table->date('certificate_expiration_date')->nullable();
            $table->boolean('tax_exempt_household')->default(0);
            $table->text('remarks')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('child_information');
    }
}
