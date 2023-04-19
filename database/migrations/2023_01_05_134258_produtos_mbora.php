<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProdutosMbora extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos_mbora', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imei', 20)->index();
            $table->integer('idcategoria')->index()->unsigned();
            $table->string('nome', 30);
            $table->integer('preco')->unsigned();
            $table->integer('quantidade')->unsigned();
            $table->string('urlImage');
            $table->string('codigoBarra', 20)->nullable();
            $table->string('tag', 30)->index();
            $table->bigInteger('visualizacao', false, false);
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
