<?php

namespace App\Console\Commands;

use App\Traits\ConnectionTrait;

use Illuminate\Console\Command;

class UpdateBexSoluciones extends Command
{
    use ConnectionTrait;

    protected $signature = 'command:update-bex-soluciones {argumento1} {argumento2}';

    protected $description = 'Command update bex soluciones';


    public function handle()
    {
        $dbbs =$this->argument('argumento1');
        $areabs = $this->argument('argumento2');
        
        $configDB = $this->connectionDB($dbbs,$areabs); 
        if($configDB == false){
            return;
        }


    }
}
