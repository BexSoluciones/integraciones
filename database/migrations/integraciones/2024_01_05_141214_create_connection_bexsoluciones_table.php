<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionBexsolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connection_bexsoluciones', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 45)->nullable();
            $table->string('host', 45)->nullable();
            $table->string('username', 45)->nullable();
            $table->string('password', 45)->nullable();
            $table->enum('area', ['bexmovil', 'bextms', 'bextramites', 'bexwms', 'ecomerce'])->nullable();
            $table->char('active', 1)->default('1');
            $table->timestamps();
            $table->integer('connection_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connection_bexsoluciones');
    }
}
