<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('connection', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('host', 45);
            $table->string('username', 45);
            $table->string('password', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connetion');
    }
};
