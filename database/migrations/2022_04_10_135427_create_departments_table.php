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

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->bigInteger('owner')->unsigned()->nullable();
            $table->json('entr_filter')->nullable();
            $table->timestamps();

            $table->foreign('owner')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
        });

        Schema::create('department_user', function (Blueprint $table) {
            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
        });

        Schema::create('department_permission', function (Blueprint $table) {
            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->bigInteger('permission_id')->unsigned()->nullable();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments_permissions');
        Schema::dropIfExists('departments_users');
        Schema::dropIfExists('departments');
    }
};
