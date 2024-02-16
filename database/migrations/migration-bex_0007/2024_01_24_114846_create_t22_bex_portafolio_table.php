<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT22BexPortafolioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t22_bex_portafolio', function (Blueprint $table) {
            $table->string('codportafolio', 15)->nullable();
            $table->string('nomportafolio', 50)->nullable();
            $table->string('estadoportafolio', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t22_bex_portafolio');
    }
}
