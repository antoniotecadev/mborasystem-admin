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
            $table->string('fabricante', 20)->nullable();
            $table->string('marca', 20)->nullable();
            $table->string('produto', 20)->nullable();
            $table->string('modelo', 20)->nullable();
            $table->string('versao', 10)->nullable();
            $table->integer('api')->default(0);
            $table->string('device', 20)->nullable();
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
