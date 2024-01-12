<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT21BexPlanProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t21_bex_plan_productos', function (Blueprint $table) {
            $table->string('pro_plancriterios', 3)->nullable();
            $table->string('descripcion')->nullable();
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
        Schema::dropIfExists('t21_bex_plan_productos');
    }
}
