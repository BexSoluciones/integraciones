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
        Schema::create('t22_bex_portafolio', function (Blueprint $table) {
            $table->string('f22_codportafolio', 15)->nullable();
            $table->string('f22_nomportafolio', 50)->nullable();
            $table->string('f22_estadoportafolio', 1)->nullable()->default('A');
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
};
