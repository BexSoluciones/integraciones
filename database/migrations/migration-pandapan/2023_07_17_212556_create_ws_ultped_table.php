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
        Schema::create('ws_ultped', function (Blueprint $table) {
            $table->string('codempresa', 10)->nullable();
            $table->string('tercvendedor', 15)->nullable();
            $table->string('nitcliente', 15)->nullable()->index('i_nit2');
            $table->string('succliente', 3)->nullable()->index('i_suc2');
            $table->string('codproducto', 15)->nullable();
            $table->string('cantidad', 20)->nullable();
            $table->string('codcliente', 17)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_ultped');
    }
};
