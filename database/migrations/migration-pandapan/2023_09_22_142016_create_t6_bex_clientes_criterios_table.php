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
        Schema::create('t6_bex_clientes_criterios', function (Blueprint $table) {
            $table->string('f6_cli_nitcliente', 15)->nullable()->index('i_clicriterios4');
            $table->string('f6_cli_dvcliente', 1)->nullable()->index('i_clicriterios5');
            $table->string('f6_cli_succliente', 4)->nullable()->index('i_clicriterios6');
            $table->string('f6_cli_vendedor', 4)->nullable();
            $table->string('f6_cli_plancriterios', 3)->nullable()->index('i_clicriterios2');
            $table->string('f6_cli_criteriomayor', 10)->nullable()->index('i_clicriterios3');
            $table->string('f6_cli_grupodscto', 4)->nullable();
            $table->string('f6_cli_tipocli', 4)->nullable();
            $table->string('f6_codclientealt', 15)->nullable();
            $table->integer('f6_codcliente')->nullable()->index('i_clicriterios8');

            $table->index(['f6_cli_nitcliente', 'f6_cli_dvcliente', 'f6_cli_succliente', 'f6_cli_plancriterios'], 'i_clicriterios');
            $table->index(['f6_codclientealt', 'f6_cli_succliente'], 'i_clicriterios7');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t6_bex_clientes_criterios');
    }
};
