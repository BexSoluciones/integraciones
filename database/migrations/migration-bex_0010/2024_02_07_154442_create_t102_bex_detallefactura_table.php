<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT102BexDetallefacturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t102_bex_detallefactura', function (Blueprint $table) {
            $table->string('nitcliente', 15)->nullable()->index('i_nit');
            $table->string('tipodoc', 15)->nullable()->index('i_pro');
            $table->string('numeroFactura', 15)->nullable()->index('i_obli');
            $table->string('codproducto', 15)->nullable();
            $table->string('tipoCredito', 15)->nullable();
            $table->string('nomproducto', 200)->nullable();
            $table->string('cantidad', 10)->nullable();
            $table->string('valorUnitario', 20)->nullable();
            $table->string('valorTotal', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t102_bex_detallefactura');
    }
}
