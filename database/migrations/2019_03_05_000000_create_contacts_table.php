<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->integer('codigo_equipa')->index()->unsigned();
            $table->integer('provincia_id')->index();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('nif_bi', 25);
            $table->string('email', 50)->nullable();
            $table->string('phone', 15);
            $table->string('alternative_phone', 15);
            $table->string('empresa', 20);
            $table->string('municipality', 20);
            $table->string('district', 20);
            $table->string('street', 20);
            $table->enum('estado',['0', '1']);
            $table->string('imei', 20)->index()->unique();
            $table->enum('read_contact',['0', '1', '2', '3']);
            $table->string('motivo_elimina', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
