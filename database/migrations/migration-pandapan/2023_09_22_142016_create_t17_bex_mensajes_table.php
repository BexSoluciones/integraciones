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
        Schema::create('t17_bex_mensajes', function (Blueprint $table) {
            $table->string('f17_codmensaje', 10)->nullable();
            $table->string('f17_tipomensaje', 40)->nullable();
            $table->string('f17_nommensaje', 78)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t17_bex_mensajes');
    }
};
