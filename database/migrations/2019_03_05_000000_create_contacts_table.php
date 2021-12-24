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
            $table->integer('organization_id')->nullable()->index();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('nif_bi', 25);
            $table->string('email', 50)->nullable();
            $table->string('phone', 15);
            $table->string('alternative_phone', 15)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
