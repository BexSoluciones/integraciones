<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportationDemandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importation_demand', function (Blueprint $table) {
            $table->bigInteger('id')->index('id');
            $table->string('command', 50)->nullable();
            $table->string('name_db', 20)->nullable();
            $table->string('area', 20)->nullable();
            $table->time('hour')->nullable();
            $table->date('date')->nullable();
            $table->enum('state', ['1', '2', '3', '4'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('importation_demand');
    }
}
