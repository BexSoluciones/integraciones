<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT34BexRuterosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t34_bex_ruteros', function (Blueprint $table) {
            $table->string('tercvendedor', 50)->nullable()->index('in_wsruteros_tercv');
            $table->string('dia', 50)->nullable()->index('in_wsruteros_dia');
            $table->string('dia_descrip', 50)->nullable();
            $table->string('cliente', 50)->nullable()->index('in_wsruteros_cliente');
            $table->string('dv', 1)->nullable();
            $table->string('sucursal', 50)->nullable()->index('in_wsruteros_sucursal');
            $table->string('secuencia', 50)->nullable();
            $table->string('inactivo', 1)->default('A');
            $table->string('estadodiarutero', 1)->default('A');
            
            $table->index(['cliente', 'sucursal'], 'in_wsruteros_nitsuc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t34_bex_ruteros');
    }
}
