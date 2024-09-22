<?php

use App\Models\MailHistory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('mail_address');
            $table->tinyInteger('status')->default(MailHistory::STATUS_PENDING);
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->foreignId('children_class_id')->nullable()->constrained('children_classes')->nullOnDelete();
            $table->tinyInteger('type')->default(MailHistory::TYPE_NORMAL);
            $table->foreignId('create_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('update_user_id')->nullable()->constrained('users')->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_histories');
    }
}
