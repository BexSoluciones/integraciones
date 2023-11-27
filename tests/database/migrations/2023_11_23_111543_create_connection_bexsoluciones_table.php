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
            $table->id();
            $table->string('name', 45);
            $table->string('host', 45)->nullable();
            $table->string('username', 45);
            $table->string('password', 45)->nullable();
            $table->string('alias', 45);
            $table->enum('area', ['bextms', 'bexmovil', 'bextramites', 'bexwms', 'ecommerce']);
            $table->integer('active')->default(1);
            $table->timestamps();
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
