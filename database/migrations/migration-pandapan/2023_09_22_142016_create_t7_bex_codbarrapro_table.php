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
        Schema::create('t7_bex_codbarrapro', function (Blueprint $table) {
            $table->string('f7_codbar')->nullable();
            $table->string('f7_codproducto', 50)->nullable();
            $table->integer('f7_cant_asociada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t7_bex_codbarrapro');
    }
};
