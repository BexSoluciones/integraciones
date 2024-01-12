<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT07BexCodbarraproTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t07_bex_codbarrapro', function (Blueprint $table) {
            $table->string('codbar')->nullable();
            $table->string('codproducto', 50)->nullable();
            $table->integer('cant_asociada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t07_bex_codbarrapro');
    }
}
