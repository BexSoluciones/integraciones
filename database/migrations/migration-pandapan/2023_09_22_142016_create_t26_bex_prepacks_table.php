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
        Schema::create('t26_bex_prepacks', function (Blueprint $table) {
            $table->string('f26_codprepack', 15)->nullable();
            $table->string('f26_codproducto', 15)->nullable();
            $table->string('f26_cajas', 8)->nullable();
            $table->string('f26_unidades', 8)->nullable();
            $table->string('f26_nomprepack', 40)->nullable();
            $table->string('f26_estado', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t26_bex_prepacks');
    }
};
