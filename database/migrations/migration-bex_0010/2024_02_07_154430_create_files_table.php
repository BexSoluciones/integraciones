<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('custom_migrations_id')->nullable();
            $table->string('name', 50)->nullable();
            $table->integer('stateBexMovil')->default(1);
            $table->integer('stateBexTramites')->default(1);
            $table->integer('requiredBexMovil')->default(1);
            $table->integer('requiredBexTramites')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
