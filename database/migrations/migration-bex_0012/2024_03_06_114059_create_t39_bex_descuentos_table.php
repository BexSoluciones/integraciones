<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT39BexDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t39_bex_descuentos', function (Blueprint $table) {
            $table->string('codgrupodcto', 20)->nullable();
            $table->string('codproducto', 50)->nullable();
            $table->string('dcto', 10)->nullable();
            $table->string('estadogrupodcto', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t39_bex_descuentos');
    }
}
