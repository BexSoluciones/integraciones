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
        Schema::create('bex_portafolio', function (Blueprint $table) {
            $table->string('codportafolio', 15)->nullable();
            $table->string('nomportafolio', 50)->nullable();
            $table->string('estadoportafolio', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bex_portafolio');
    }
};
