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
        Schema::create('t35_bex_ultped', function (Blueprint $table) {
            $table->string('f35_codempresa', 10)->nullable();
            $table->string('f35_tercvendedor', 15)->nullable();
            $table->string('f35_nitcliente', 15)->nullable()->index('i_nit2');
            $table->string('f35_succliente', 3)->nullable()->index('i_suc2');
            $table->string('f35_codproducto', 15)->nullable();
            $table->string('f35_cantidad', 20)->nullable();
            $table->string('f35_codcliente', 17)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t35_bex_ultped');
    }
};
