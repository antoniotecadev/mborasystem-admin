<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Agentes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agentes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->integer('equipa_id')->index()->unsigned();
            $table->string('nome_completo', 50);
            $table->string('bi', 14);
            $table->string('email', 50)->nullable()->unique();
            $table->string('telefone', 15);
            $table->string('telefone_alternativo', 15);
            $table->string('municipio', 20);
            $table->string('bairro', 20);
            $table->string('rua', 20);
            $table->string('banco', 50);
            $table->enum('estado',['0', '1']);
            $table->string('photo_path', 100)->nullable();
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
