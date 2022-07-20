<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLetterSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('letter_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('next_approval_by');
            $table->unsignedBigInteger('status')->default(0);
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
        Schema::dropIfExists('letter_submissions');
    }
}
