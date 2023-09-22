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
        Schema::create('t38_bex_amovil', function (Blueprint $table) {
            $table->string('f38_nitcliente', 15)->nullable();
            $table->string('f38_succliente', 4)->nullable();
            $table->string('f38_ano', 4)->nullable();
            $table->string('f38_mes', 2)->nullable();
            $table->string('f38_valor', 20)->nullable();
            $table->string('f38_tercvendedor', 4)->nullable()->index('tercvendedor');
            $table->string('f38_codcliente', 17)->nullable();
            $table->string('f38_codvendedor', 4)->nullable();

            $table->index(['f38_nitcliente', 'f38_succliente'], 'nitsuc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t38_bex_amovil');
    }
};
