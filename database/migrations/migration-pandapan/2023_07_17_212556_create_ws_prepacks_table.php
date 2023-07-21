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
        Schema::create('ws_prepacks', function (Blueprint $table) {
            $table->string('codprepack', 15)->nullable();
            $table->string('codproducto', 15)->nullable();
            $table->string('cajas', 8)->nullable();
            $table->string('unidades', 8)->nullable();
            $table->string('nomprepack', 40)->nullable();
            $table->string('estado', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_prepacks');
    }
};
