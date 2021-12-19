<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('region'); //название района
            $table->string('city'); //главный город района
            $table->string('leader')->nullable(); //глава района
            $table->integer('population'); //население района
            $table->string('area')->nullable(); //площадь квадратных метров
            $table->integer('amw')->nullable(); //средняя заработная плата (Average monthly wage)
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
        Schema::dropIfExists('areas');
    }
}
