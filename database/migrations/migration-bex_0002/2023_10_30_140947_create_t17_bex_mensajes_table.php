<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT17BexMensajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t17_bex_mensajes', function (Blueprint $table) {
            $table->string('codmensaje', 10)->nullable();
            $table->string('tipomensaje', 40)->nullable();
            $table->string('nommensaje', 78)->nullable();
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
}
