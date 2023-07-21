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
        Schema::create('ws_referencias_alternas', function (Blueprint $table) {
            $table->string('fecha', 20)->nullable();
            $table->string('cia', 20)->nullable();
            $table->string('item', 50)->nullable();
            $table->string('referencia', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_referencias_alternas');
    }
};
