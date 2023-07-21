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
        Schema::create('ws_tr_motdev', function (Blueprint $table) {
            $table->string('codmotdev', 4)->nullable()->index('i_s1e_tr_motdev_codmotdev');
            $table->string('nommotdev', 50)->nullable();
            $table->string('estadomotivo', 1)->nullable()->default('A');
            $table->string('estadomotivo2', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_tr_motdev');
    }
};
