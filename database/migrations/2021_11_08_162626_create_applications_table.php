<?php

use App\Models\Application;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('attendance_id')->nullable();
            $table->foreignId('application_class_id')->nullable()->constrained('application_classes')->nullOnDelete();
            $table->unsignedTinyInteger('status')->default(Application::STATUS_PENDING);
            $table->dateTime('application_datetime')->nullable();
            $table->dateTime('approval_datetime')->nullable();
            $table->string('reason', 200)->nullable();
            $table->dateTime('time_before_correction')->nullable();
            $table->dateTime('time_after_correction')->nullable();
            $table->foreignId('reason_id')->nullable();

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
        Schema::dropIfExists('applications');
    }
}
