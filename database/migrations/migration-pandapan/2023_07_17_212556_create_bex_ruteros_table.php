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
        Schema::create('bex_ruteros', function (Blueprint $table) {
            $table->string('tercvendedor', 50)->nullable()->index('in_s1eruteros_tercv');
            $table->string('dia', 50)->nullable()->index('in_s1eruteros_dia');
            $table->string('dia_descrip', 50)->nullable();
            $table->string('cliente', 50)->nullable()->index('in_s1eruteros_cliente');
            $table->string('dv', 1)->nullable();
            $table->string('sucursal', 50)->nullable()->index('in_s1eruteros_sucursal');
            $table->string('secuencia', 50)->nullable();
            $table->string('inactivo', 1)->nullable();
            $table->string('estadodiarutero', 1)->nullable()->default('A');

            $table->index(['cliente', 'sucursal'], 'in_s1eruteros_nitsuc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bex_ruteros');
    }
};
