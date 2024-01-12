<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT36BexVendedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t36_bex_vendedores', function (Blueprint $table) {
            $table->string('compania', 1)->nullable();
            $table->string('tercvendedor', 4)->nullable();
            $table->string('centroop', 50)->nullable();
            $table->string('tipodoc', 20)->nullable();
            $table->string('estado', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t36_bex_vendedores');
    }
}
