<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->string('name', 20);
            $table->string('municipality', 20);
            $table->string('district', 20);
            $table->string('street', 20);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}

