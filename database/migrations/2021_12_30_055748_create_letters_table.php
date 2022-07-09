<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('letter_no');
            $table->string('title');
            $table->string('date')->nullable();
            $table->string('date_approval')->nullable();
            $table->unsignedBigInteger('approval_by')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->string('letter_file')->nullable();
            $table->unsignedBigInteger('status')->default(0); // belum tervalidasi
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letters');
    }
}
