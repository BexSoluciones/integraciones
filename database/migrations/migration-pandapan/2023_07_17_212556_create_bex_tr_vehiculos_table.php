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
        Schema::create('bex_tr_vehiculos', function (Blueprint $table) {
            $table->integer('consecutivo', true);
            $table->string('placa')->nullable()->index('i_s1e_tr_vehiculos_placa');
            $table->string('nombre')->nullable();
            $table->string('identificacion')->nullable();
            $table->string('pesomax')->nullable();
            $table->string('volmax')->nullable();
            $table->string('tiponc')->nullable();
            $table->string('estadovendedor', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bex_tr_vehiculos');
    }
};
