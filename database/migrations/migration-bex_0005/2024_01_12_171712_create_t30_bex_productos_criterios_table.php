<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT30BexProductosCriteriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t30_bex_productos_criterios', function (Blueprint $table) {
            $table->string('pro_codproducto', 20)->nullable()->index('i_5');
            $table->string('pro_plan', 3)->nullable()->index('i_1');
            $table->string('pro_criteriomayor', 4)->nullable()->index('i_2');
            $table->string('pro_grupodscto', 4)->nullable();
            $table->string('pro_tipoinv', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t30_bex_productos_criterios');
    }
}
