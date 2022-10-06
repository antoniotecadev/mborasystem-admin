<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Dispositivos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispositivos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contact_id')->index();
            $table->string('fabricante', 20);
            $table->string('marca', 20);
            $table->string('produto', 20);
            $table->string('modelo', 20);
            $table->string('versao', 10);
            $table->integer('api');
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
