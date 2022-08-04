<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLetterHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('letter_id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('send_by');
            $table->unsignedBigInteger('is_read')->default(0);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('letter_histories');
    }
}
