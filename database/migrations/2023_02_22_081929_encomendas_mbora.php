<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EncomendasMbora extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encomendas_mbora', function(Blueprint $table){
            $table->increments('id');
            $table->integer('id_contacts', false, false)->index();
            $table->string('id_users_mbora')->index();
            $table->integer('id_produtos_mbora', false, false)->index();
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
