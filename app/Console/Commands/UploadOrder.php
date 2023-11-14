<?php

namespace App\Console\Commands;

use App\Traits\ConnectionTrait;
use App\Traits\GetOrderTrait;
use Illuminate\Console\Command;

class UploadOrder extends Command
{
    use ConnectionTrait, GetOrderTrait;

    protected $signature = 'command:upload-order {database} {area} {closing}';
    protected $description = 'Command upload order to ERP';

    public function handle()
    {
        $db = $this->argument('database');
        $area = $this->argument('area');
        $closing = $this->argument('closing');

        $configDB = $this->connectionDB($db,$area); 
        if($configDB == false){
            return;
        }

        $orders=$this->getOrderHeder($db,$area,$closing);
        if($orders == true){
            $this->info('◘ Proceso getOrderHeder finalizado');
            return;
        }
      
        if(!empty($orders)){
            $orderDetails =$this->getOrderDetail($orders);
            $this->info('◘ Proceso getOrderDetail finalizado');
            return;
        }   
    }
}
