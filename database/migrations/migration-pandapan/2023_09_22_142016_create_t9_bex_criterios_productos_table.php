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
        Schema::create('t9_bex_criterios_productos', function (Blueprint $table) {
            $table->string('f9_pro_plancriterios', 3)->nullable();
            $table->string('f9_pro_criteriomayor', 10)->nullable();
            $table->string('f9_descripcion')->nullable();
            $table->string('f9_estado', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t9_bex_criterios_productos');
    }
};
