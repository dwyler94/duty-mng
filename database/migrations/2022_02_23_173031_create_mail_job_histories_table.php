<?php

use App\Models\MailHistory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailJobHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_job_histories', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->foreignId('children_class_id')->nullable()->constrained('children_classes')->nullOnDelete();
            $table->tinyInteger('type')->default(MailHistory::TYPE_NORMAL);
            $table->integer('cnt')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('office_id')->constrained('offices');
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
        Schema::dropIfExists('mail_job_histories');
    }
}
