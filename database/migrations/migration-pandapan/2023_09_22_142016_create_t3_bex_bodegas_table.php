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
        Schema::create('t3_bex_bodegas', function (Blueprint $table) {
            $table->string('f3_codigo', 5)->nullable();
            $table->string('f3_descripcion', 50)->nullable();
            $table->string('f3_estadobodega', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t3_bex_bodegas');
    }
};
