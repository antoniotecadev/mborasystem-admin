<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pagamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->integer('contact_id')->index()->unsigned();
            $table->enum('pacote', ['0', '1', '2']);
            $table->enum('tipo_pagamento', ['1', '3', '6', '12']);
            $table->integer('preco')->unsigned();
            $table->date('inicio');
            $table->date('fim');
            $table->enum('pagamento', ['0', '1']);
            $table->string('motivo_elimina', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();
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
