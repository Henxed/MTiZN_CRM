<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnterprisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('enterprises', function (Blueprint $table)
      {
          $table->bigIncrements('id');
          $table->integer('area_id')->unsigned();
          $table->unsignedBigInteger('enterprises_id')->nullable();
          $table->foreign('enterprises_id')->references('id')->on('enterprises');
          $table->string('name')->nullable();
          $table->string('amy')->nullable()->comment('средняя ЗП за текущий год');
          $table->string('am')->nullable()->comment('средняя ЗП');
          $table->string('cw')->nullable()->comment('Численность работников на дату');
          $table->string('inn')->nullable()->unique();
          $table->timestamps();
      });
      Schema::table('enterprises', function (Blueprint $table)
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
      Schema::dropIfExists('enterprises');
    }
}
