<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateSettingsTable extends Migration
{

	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
            $table->string('key')->unique()->index();
            $table->string('label')->nullable();
			$table->text('value')->nullable();
		});
	}


	public function down()
	{
		Schema::drop('settings');
	}
}
