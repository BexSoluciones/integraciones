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
        Schema::create('t25_bex_precios', function (Blueprint $table) {
            $table->string('f25_lista', 50)->nullable();
            $table->string('f25_producto', 50)->nullable();
            $table->string('f25_precio', 50)->nullable();
            $table->string('f25_ico', 50)->nullable();
            $table->string('f25_preciomin', 50)->nullable();
            $table->string('f25_preciomax', 50)->nullable();
            $table->string('f25_estadoprecio', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t25_bex_precios');
    }
};
