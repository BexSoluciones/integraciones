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
        Schema::create('t12_bex_dptos', function (Blueprint $table) {
            $table->string('f12_codpais', 5)->nullable();
            $table->string('f12_coddpto', 5)->nullable();
            $table->string('f12_descripcion', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t12_bex_dptos');
    }
};
