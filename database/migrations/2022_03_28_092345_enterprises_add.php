<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnterprisesAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enterprises', function (Blueprint $table)
        {
            $table->string('rns')->nullable()->comment('Регистрационный номер страхователя');
            $table->bigInteger('status_id')->unsigned()->nullable()->comment('Состояние страхователя');
            $table->string('okvd')->nullable()->comment('Код основного ОКВЭДа страхователя');
            $table->string('okvd_name')->nullable()->comment('Наименование основного ОКВЭДа страхователя');

            $table->integer('ane')->nullable()->comment('Среднесписочная численность работников');
            $table->integer('nde')->nullable()->comment('Численность работающих инвалидов');
            $table->integer('factors')->nullable()->comment('Численность работников, занятых на работах с вредными и (или) опасными производственными факторами');
            $table->integer('total_jobs')->nullable()->comment('Общее количество рабочих мест');
            $table->integer('workplaces_respect')->nullable()->comment('Кол-во рабочих мест, в отношении условий труда на которых проведена специальная оценка условий труда на начало года, всего');
            $table->integer('workplaces_three')->nullable()->comment('Кол-во рабочих мест, в отношении условий труда на которых проведена специальная оценка условий труда на начало года, в том числе отнесенных к вредным и опасным условиям труда 3 класс');
            $table->integer('workplaces_four')->nullable()->comment('Кол-во рабочих мест, в отношении условий труда на которых проведена специальная оценка условий труда на начало года, в том числе отнесенных к вредным и опасным условиям труда 4 класс');
            $table->integer('total_factors')->nullable()->comment('Общее количество работников, занятых на работах с вредными и (или) опасными производственными факторами, подлежащих обязательным предварительным и периодическим медицинским осмотрам (чел.)');
            $table->integer('start_year_factors')->nullable()->comment('Количество работников, занятых на работах с вредными и (или) опасными производственными факторами, прошедших обязательные предварительные и периодические медицинские осмотры на начало года (чел.)');

            $table->text('address')->nullable()->comment('Адрес (по данным страхователя из расчета по форме 4-ФСС)');

            $table->foreign('status_id')->references('code')->on('statuses')->onDelete('SET NULL')->onUpdate('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      //Schema::dropIfExists('enterprises');
    }
}
