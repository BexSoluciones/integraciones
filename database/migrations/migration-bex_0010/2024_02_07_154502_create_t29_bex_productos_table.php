<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT29BexProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t29_bex_productos', function (Blueprint $table) {
            $table->string('codigo', 50)->nullable();
            $table->string('descripcion')->nullable();
            $table->string('codunidademp', 10)->nullable();
            $table->string('peso', 20)->nullable();
            $table->string('codproveedor', 20)->nullable();
            $table->string('nomproveedor', 50)->nullable();
            $table->integer('unidadventa')->nullable();
            $table->string('codindadventa', 10)->nullable();
            $table->string('estado', 1)->default('A');
            $table->string('estado_unidademp', 1)->default('A');
            $table->string('estadoproveedor', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t29_bex_productos');
    }
}
