<?php

use App\Model\Areas;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RegionAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->string('commissions_c')->nullable()->comment('Установленный контроный показатель (чел)');
            $table->string('commissions_t')->nullable()->comment('Заключено трудовых договоров');
            $table->string('commissions')->nullable()->comment('Количество проведенных территориальных межведомственных комиссий по вопросам');
            $table->string('in_employment')->nullable()->comment('Показатель по снижению неформальной занятости');
        });

        $existing_threads = Areas::all();

        foreach($existing_threads as $threads)
        {
            $threads->commissions_c = rand(1, 800);
            $threads->commissions_t = rand(1, 80);
            $threads->commissions = rand(1, 18);
            $threads->in_employment = rand(1, 15);
            $threads->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn(['commissions', 'in_employment']);
        });
    }
}
