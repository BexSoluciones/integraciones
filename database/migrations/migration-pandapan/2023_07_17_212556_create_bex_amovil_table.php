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
        Schema::create('bex_amovil', function (Blueprint $table) {
            $table->string('nitcliente', 15)->nullable();
            $table->string('succliente', 4)->nullable();
            $table->string('ano', 4)->nullable();
            $table->string('mes', 2)->nullable();
            $table->string('valor', 20)->nullable();
            $table->string('tercvendedor', 4)->nullable()->index('tercvendedor');
            $table->string('codcliente', 17)->nullable();
            $table->string('codvendedor', 4)->nullable();

            $table->index(['nitcliente', 'succliente'], 'nitsuc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bex_amovil');
    }
};
