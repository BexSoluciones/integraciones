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
        Schema::create('ws_mpios', function (Blueprint $table) {
            $table->string('codpais', 5)->nullable();
            $table->string('coddpto', 5)->nullable();
            $table->string('codmpio', 5)->nullable();
            $table->string('descripcion', 50)->nullable();
            $table->string('indicador', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_mpios');
    }
};
