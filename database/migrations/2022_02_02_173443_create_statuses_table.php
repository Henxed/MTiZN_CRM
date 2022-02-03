<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::create('areas_distance', function (Blueprint $table) {
        //     $table->integer('area_id')->unsigned()->nullable();
        //     $table->integer('go_id')->unsigned()->nullable();
        //     $table->string('distance')->nullable();

        //     $table->foreign('area_id')->references('id')->on('areas')->onDelete('SET NULL')->onUpdate('CASCADE');
        //     $table->foreign('go_id')->references('id')->on('areas')->onDelete('SET NULL')->onUpdate('CASCADE');
        // });

        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('code')->unsigned()->nullable()->index()->unique(); // уникальные номера ошибок. Вдруг нужно текст поменять, тогда можно получать коды статуса
            $table->string('model')->nullable(); // для какого модуля
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('regists_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('regists', function($table) {
            $table->bigInteger('category_id')->after('status')->unsigned()->nullable();
            $table->bigInteger('status_id')->after('status')->unsigned()->nullable();

            $table->foreign('status_id')->references('code')->on('statuses')->onDelete('set null')->onUpdate('CASCADE');
            $table->foreign('category_id')->references('id')->on('regists_categories')->onDelete('set null')->onUpdate('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regists', function (Blueprint $table) {
            $table->dropForeign('regists_status_id_foreign');
            $table->dropForeign('regists_category_id_foreign');
            $table->dropColumn('category_id');
            $table->dropColumn('status_id');
        });
        Schema::dropIfExists('regists_categories');
        Schema::dropIfExists('statuses');
    }
}
