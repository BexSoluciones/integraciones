<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT06BexClientesCriteriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t06_bex_clientes_criterios', function (Blueprint $table) {
            $table->string('cli_nitcliente', 15)->nullable()->index('i_clicriterios4');
            $table->string('cli_dvcliente', 1)->nullable()->index('i_clicriterios5');
            $table->string('cli_succliente', 4)->nullable()->index('i_clicriterios6');
            $table->string('cli_vendedor', 4)->nullable();
            $table->string('cli_plancriterios', 3)->nullable()->index('i_clicriterios2');
            $table->string('cli_criteriomayor', 10)->nullable()->index('i_clicriterios3');
            $table->string('cli_grupodscto', 4)->nullable();
            $table->string('cli_tipocli', 4)->nullable();
            $table->string('codclientealt', 15)->nullable();
            $table->integer('codcliente')->nullable()->index('i_clicriterios8');
            
            $table->index(['cli_nitcliente', 'cli_dvcliente', 'cli_succliente', 'cli_plancriterios'], 'i_clicriterios');
            $table->index(['codclientealt', 'cli_succliente'], 'i_clicriterios7');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t06_bex_clientes_criterios');
    }
}
