<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ContactBook;
class AddStateToContactBooksTable extends Migration
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
            $table->unsignedTinyInteger('status')->default(ContactBook::STATUS_INCOMPLETE);
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
            //
        });
    }
}
