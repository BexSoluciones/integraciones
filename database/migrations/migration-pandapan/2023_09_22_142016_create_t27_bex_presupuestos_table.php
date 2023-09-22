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
        Schema::create('t27_bex_presupuestos', function (Blueprint $table) {
            $table->string('f27_tercvendedor', 4)->nullable();
            $table->date('f27_fecpptovendia')->nullable();
            $table->decimal('f27_precio', 16)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t27_bex_presupuestos');
    }
};
