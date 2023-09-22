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
        Schema::create('t21_bex_plan_productos', function (Blueprint $table) {
            $table->string('f21_pro_plancriterios', 3)->nullable();
            $table->string('f21_descripcion')->nullable();
            $table->string('f21_estado', 1)->nullable()->default('A');
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
};
