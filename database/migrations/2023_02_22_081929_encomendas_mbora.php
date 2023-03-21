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
            $table->string('imei_contacts', 20)->index();
            $table->integer('id_users_mbora')->index();
            $table->integer('id_produtos_mbora', false, false)->index();
            $table->string('client_phone');
            $table->string('client_address', 50);
            $table->string('client_info_ad', 50)->nullable();
            $table->json('client_coordinate');
            $table->boolean('estado')->default(false);
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
