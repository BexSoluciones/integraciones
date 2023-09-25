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
        Schema::create('t30_bex_productos_criterios', function (Blueprint $table) {
            $table->string('f30_pro_codproducto', 20)->nullable()->index('i_5');
            $table->string('f30_pro_plan', 3)->nullable()->index('i_1');
            $table->string('f30_pro_criteriomayor', 4)->nullable()->index('i_2');
            $table->string('f30_pro_grupodscto', 4)->nullable()->index('i_3');
            $table->string('f30_pro_tipoinv', 10)->nullable()->index('i_4');
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
};
