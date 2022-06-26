<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safeties', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('enterprise_id')->unsigned();
            $table->timestamp('collective_agreement')->comment('Дата заключения коллективного договора');
            $table->integer('sum_contractual')->comment('Количество работников, охваченных коллективно-договорными отношениями, чел.');

            // Несчастные случаи на производстве
            // Групповые
            $table->timestamp('accidents_group_at')->nullable()->comment('Дата');
            $table->integer('accidents_group')->default(0)->nullable()->comment('Кол-во пострадавших, чел.');
            // Тяжелые
            $table->timestamp('accidents_heavy_at')->nullable()->comment('Дата');
            $table->integer('accidents_heavy')->default(0)->nullable()->comment('Кол-во пострадавших, чел.');
            // Смертельные
            $table->timestamp('accidents_deadly_at')->nullable()->comment('Дата');
            $table->integer('accidents_deadly')->default(0)->nullable()->comment('Кол-во пострадавших, чел.');

            // Обучены по охране труда, чел.
            $table->integer('in_total')->default(0)->comment('всего');
            $table->integer('start_year')->default(0)->comment('с начала года');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('enterprise_id')->references('id')->on('enterprises')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('safeties');
    }
};
