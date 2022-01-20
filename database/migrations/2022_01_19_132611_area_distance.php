<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AreaDistance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('areas_distance', function (Blueprint $table) {
            $table->integer('area_id')->unsigned()->nullable();
            $table->integer('go_id')->unsigned()->nullable();
            $table->string('distance')->nullable();

            $table->foreign('area_id')->references('id')->on('areas')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->foreign('go_id')->references('id')->on('areas')->onDelete('SET NULL')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas_distance');
    }
}
