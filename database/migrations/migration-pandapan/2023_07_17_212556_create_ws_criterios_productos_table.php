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
        Schema::create('ws_criterios_productos', function (Blueprint $table) {
            $table->string('pro_plancriterios', 3)->nullable();
            $table->string('pro_criteriomayor', 10)->nullable();
            $table->string('descripcion')->nullable();
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
        Schema::dropIfExists('ws_criterios_productos');
    }
};
