<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AreaChild extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {

       Schema::table('areas', function (Blueprint $table)
       {
         $table->integer('areas_id')->unsigned()->nullable();
         $table->foreign('areas_id')->references('id')->on('areas');
       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
       //
     }
}
