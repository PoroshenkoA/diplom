<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',255);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->rememberToken();
            $table->integer('leaderLoad')->nullable();
            $table->integer('userTypeID')->unsigned();
            $table->integer('groupID')->unsigned();
            $table->string('post', 100)->nullable();
            $table->timestamps();
            $table->foreign('userTypeID')->references('id')->on('userTypes');
            $table->foreign('groupID')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
