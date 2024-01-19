<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT101BexObligacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t101_bex_obligaciones', function (Blueprint $table) {
            $table->string('codempresa', 4)->nullable();
            $table->string('codclientealt', 15)->nullable();
            $table->string('nitcliente', 15)->nullable()->index('i_nit');
            $table->string('nomcliente', 50)->nullable();
            $table->string('telcliente1', 15)->nullable();
            $table->string('telcliente2', 15)->nullable();
            $table->string('numobligacion', 15)->nullable();
            $table->string('tipocredito', 10)->nullable();
            $table->date('fecfactura')->nullable();
            $table->date('fecven')->nullable();
            $table->string('diasmora', 10)->nullable();
            $table->string('valtotcredito', 15)->nullable();
            $table->string('valacobrar', 15)->nullable();
            $table->string('valenmora', 10)->nullable();
            $table->string('regional', 50)->nullable();
            $table->string('codvendedor', 5)->nullable()->index('i_ven');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t101_bex_obligaciones');
    }
}
