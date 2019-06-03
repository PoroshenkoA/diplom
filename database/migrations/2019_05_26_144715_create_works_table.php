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
            $table->bigInteger('studentID')->unsigned();
            $table->bigInteger('leaderID')->unsigned();
            $table->foreign('studentID')->references('id')->on('users');
            $table->foreign('leaderID')->references('id')->on('users');
            $table->string('themeUkr', 255)->nullable();
            $table->string('themeEn', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->bigInteger('dateID')->unsigned()->nullable();
            $table->integer('realPages')->nullable();
            $table->integer('graphicPages')->nullable();
            $table->bigInteger('rev1')->unsigned()->nullable();
            $table->bigInteger('rev2')->unsigned()->nullable();
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
