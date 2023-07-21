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
        Schema::create('ws_productos_criterios_av', function (Blueprint $table) {
            $table->string('pro_codproducto', 20)->nullable();
            $table->string('pro_plan', 3)->nullable()->index('i_1');
            $table->string('pro_criteriomayor', 4)->nullable()->index('i_2');
            $table->string('pro_grupodscto', 4)->nullable()->index('i_3');
            $table->string('pro_tipoinv', 10)->nullable()->index('i_4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_productos_criterios_av');
    }
};
