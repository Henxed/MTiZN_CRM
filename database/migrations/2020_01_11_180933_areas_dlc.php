<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AreasDlc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('areas', function (Blueprint $table) {
          $table->string('subject')->nullable()->comment('Число хозяйствующих субъектов');
          $table->string('contract')->nullable()->comment('Заключенные коллективные договора');
          $table->string('lvl')->nullable()->comment('Уровень безработицы');
          $table->string('unemployed')->nullable()->comment('Численность зарегистрированных безработных');
          $table->string('vacancy')->nullable()->comment('Количество вакансий');
          $table->string('tension')->nullable()->comment("Напряженность");
          $table->string('employed')->nullable()->comment('Трудоустроено');
          $table->string('cripple')->nullable()->comment('Численность инвалидов');
          $table->string('cripple_worked')->nullable()->comment('из них заняты трудовой деятельностью');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('areas', function (Blueprint $table) {
          $table->dropColumn(['subject', 'contract', 'lvl', 'unemployed', 'vacancy', 'tension', 'employed', 'cripple', 'cripple_worked']);
      });
    }
}
