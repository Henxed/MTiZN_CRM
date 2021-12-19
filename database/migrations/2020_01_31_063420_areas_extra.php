<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AreasExtra extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('areas_extra', function (Blueprint $table)
    {
        $table->increments('id');
        $table->integer('area_id')->unsigned();

        $table->string('school')->nullable();
        $table->string('vvuz')->nullable();
        $table->string('ssuz')->nullable();
        $table->string('detdom')->nullable();

        //$table->string('nou')->nullable()->comment("Численность безработных");
        //$table->string('ur')->nullable()->comment("Уровень безработицы");
        //$table->string('jobs')->nullable()->comment("Вакансии");

        $table->timestamps();
    });
    Schema::table('areas_extra', function (Blueprint $table)
    {
        $table->foreign('area_id')->references('id')->on('areas')->onDelete('CASCADE')->onUpdate('CASCADE');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      Schema::dropIfExists('areas_extra');
  }
}
