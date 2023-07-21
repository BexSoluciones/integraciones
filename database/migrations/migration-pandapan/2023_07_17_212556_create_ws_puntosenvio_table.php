<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ws_puntosenvio', function (Blueprint $table) {
            $table->string('codigo', 15)->nullable();
            $table->string('dv', 1)->nullable();
            $table->string('suc', 4)->nullable();
            $table->string('codpde', 15)->nullable();
            $table->string('despde')->nullable();
            $table->integer('codcliente')->nullable();
            $table->string('estadopunto', 50)->nullable();
            $table->string('vendedor', 50)->nullable();
            $table->string('codvendedor', 4)->nullable();
            $table->string('estado', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_puntosenvio');
    }
};
