<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT25BexPreciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t25_bex_precios', function (Blueprint $table) {
            $table->string('lista', 50)->nullable();
            $table->string('producto', 50)->nullable();
            $table->string('precio', 50)->nullable();
            $table->string('ico', 50)->nullable();
            $table->string('estadoprecio', 1)->default('A');
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
}
