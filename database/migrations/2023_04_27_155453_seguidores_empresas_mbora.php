<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeguidoresEmpresasMbora extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguidores_empresas_mbora', function(Blueprint $table) {
            $table->increments('id');
            $table->string('id_empresas_mbora', 20)->index();
            $table->integer('id_users_mbora')->index()->unsigned();
            $table->tinyInteger('estado', 1)->default(1);
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
