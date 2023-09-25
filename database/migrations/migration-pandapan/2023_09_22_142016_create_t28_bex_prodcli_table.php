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
        Schema::create('t28_bex_prodcli', function (Blueprint $table) {
            $table->string('f28_codempresa', 10)->nullable();
            $table->string('f28_tercvendedor', 15)->nullable();
            $table->string('f28_nitcliente', 15)->nullable()->index('i_nit2');
            $table->string('f28_succliente', 3)->nullable()->index('i_suc2');
            $table->string('f28_codproducto', 15)->nullable();
            $table->string('f28_cantidad', 20)->nullable();
            $table->string('f28_codcliente', 17)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t28_bex_prodcli');
    }
};
