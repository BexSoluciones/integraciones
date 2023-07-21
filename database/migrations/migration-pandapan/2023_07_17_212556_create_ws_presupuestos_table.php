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
        Schema::create('ws_presupuestos', function (Blueprint $table) {
            $table->string('tercvendedor', 4)->nullable();
            $table->date('fecpptovendia')->nullable();
            $table->decimal('precio', 16)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_presupuestos');
    }
};
