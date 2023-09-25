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
        Schema::create('t20_bex_plan_clientes', function (Blueprint $table) {
            $table->string('f20_cli_plancriterios', 3)->nullable();
            $table->string('f20_descripcion')->nullable();
            $table->string('f20_estado', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t20_bex_plan_clientes');
    }
};
