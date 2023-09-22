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
        Schema::create('t18_bex_mpios', function (Blueprint $table) {
            $table->string('f18_codpais', 5)->nullable();
            $table->string('f18_coddpto', 5)->nullable();
            $table->string('f18_codmpio', 5)->nullable();
            $table->string('f18_descripcion', 50)->nullable();
            $table->string('f18_indicador', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t18_bex_mpios');
    }
};
