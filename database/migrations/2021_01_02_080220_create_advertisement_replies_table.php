<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisement_replies', function (Blueprint $table) {
            $table->id();

            $table->string('cv_download_url');
            $table->string('cover_letter', 10000);

            $table->unsignedBigInteger('advertisement_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('advertisement_id')->references('id')->on('advertisements');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('advertisement_replies');
    }
}
