<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProtectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('workID')->unsigned();
            $table->foreign('workID')->references('id')->on('works');
            $table->string('protocol', 30)->nullable();
            $table->integer('rate')->nullable();
            $table->boolean('recommendation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('protections');
    }
}
