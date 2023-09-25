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
        Schema::create('t33_bex_referencias_alternas', function (Blueprint $table) {
            $table->string('f33_fecha', 20)->nullable();
            $table->string('f33_cia', 20)->nullable();
            $table->string('f33_item', 50)->nullable();
            $table->string('f33_referencia', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t33_bex_referencias_alternas');
    }
};
