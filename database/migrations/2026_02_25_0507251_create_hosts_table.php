<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateHostsTable extends Migration
{
    public function up()
    {
        Schema::create('hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('ssh_user')->nullable();
            $table->integer('port')->default(80);
            $table->string('ip')->nullable();
            $table->string('status')->default('unknown');
            $table->json('custom_properties')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hosts');
    }
}