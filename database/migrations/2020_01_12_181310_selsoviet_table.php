<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SelsovietTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('selsoviets', function (Blueprint $table)
      {
          $table->increments('id');
          $table->integer('area_id')->unsigned()->nullable();
          $table->string('name'); //название
          $table->text('content')->nullable();
          $table->timestamps();
      });
      Schema::table('selsoviets', function (Blueprint $table)
      {
          $table->foreign('area_id')->references('id')->on('areas')->onDelete('SET NULL')->onUpdate('CASCADE');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selsoviets');
    }
}
