<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('studentID')->unsigned();
            $table->integer('leaderID')->unsigned();
            $table->foreign('studentID')->references('id')->on('users');
            $table->foreign('leaderID')->references('id')->on('users');
            $table->string('themeUkr', 255)->nullable();
            $table->string('themeEn', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->integer('dateID')->unsigned();
            $table->integer('realPages')->nullable();
            $table->integer('graphicPages')->nullable();
            $table->integer('rev1')->unsigned();
            $table->integer('rev2')->unsigned();
            $table->foreign('dateID')->references('id')->on('dateDef');
            $table->foreign('rev1')->references('id')->on('reviews');
            $table->foreign('rev2')->references('id')->on('reviews');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('works');
    }
}
