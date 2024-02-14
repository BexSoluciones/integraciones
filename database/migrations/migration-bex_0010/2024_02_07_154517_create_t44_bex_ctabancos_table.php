<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT44BexCtaBancosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t44_bex_ctabancos', function (Blueprint $table) {
            $table->string('ctabanco', 3)->nullable();
            $table->string('ctanombanco', 40)->nullable();
            $table->string('codbanco', 40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t44_bex_ctabancos');
    }
}
