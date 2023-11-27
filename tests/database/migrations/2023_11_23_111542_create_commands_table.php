<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commands', function (Blueprint $table) {
            $table->string('alias', 45);
            $table->string('command', 50)->nullable();
            $table->string('name_db', 50)->nullable();
            $table->string('cron_expression', 50)->nullable();
            $table->enum('area', ['bexmovil', 'bextramites', 'bextms', 'bexwms', 'ecommerce']);
            $table->enum('cod_area', ['0', '1', '2', '3', '4', '5']);
            $table->enum('state', ['0', '1']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commands');
    }
}
