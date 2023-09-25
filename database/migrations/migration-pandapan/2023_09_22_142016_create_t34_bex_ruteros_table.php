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
        Schema::create('t34_bex_ruteros', function (Blueprint $table) {
            $table->string('f34_tercvendedor', 50)->nullable()->index('in_wsruteros_tercv');
            $table->string('f34_dia', 50)->nullable()->index('in_wsruteros_dia');
            $table->string('f34_dia_descrip', 50)->nullable();
            $table->string('f34_cliente', 50)->nullable()->index('in_wsruteros_cliente');
            $table->string('f34_dv', 1)->nullable();
            $table->string('f34_sucursal', 50)->nullable()->index('in_wsruteros_sucursal');
            $table->string('f34_secuencia', 50)->nullable();
            $table->string('f34_inactivo', 1)->nullable();
            $table->string('f34_estadodiarutero', 1)->nullable()->default('A');

            $table->index(['f34_cliente', 'f34_sucursal'], 'in_wsruteros_nitsuc');
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
};
