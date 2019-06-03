<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('visa')->default(false);
            $table->integer('studentPriority');
            $table->integer('leaderPriority')->default(2);
            $table->bigInteger('studentID')->unsigned();
            $table->bigInteger('leaderID')->unsigned();
            $table->foreign('studentID')->references('id')->on('users');
            $table->foreign('leaderID')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
