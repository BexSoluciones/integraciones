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
        Schema::create('ws_ventranactivo', function (Blueprint $table) {
            $table->string('tercvendedor', 4)->nullable();
            $table->string('vendedor_activo', 2)->nullable();
            $table->string('transportador_activo', 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_ventranactivo');
    }
};
