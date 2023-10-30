<?php

namespace App\Console\Commands;

use App\Traits\ConnectionTrait;
use App\Traits\GetorderTrait;
use Illuminate\Console\Command;

class UploadOrder extends Command
{
    use ConnectionTrait, GetorderTrait;

    protected $signature = 'command:upload-order {database} {area}';

    protected $description = 'Command upload order to ERP';


    public function handle()
    {
        $db = $this->argument('database');
        $area = $this->argument('area');

        $configDB = $this->connectionDB($db,$area); 
        if($configDB == false){
            return;
        }

        $orders=$this->getOrderHeder('0','4');
        // print_r($orders);
        // dd();
        if(!empty($orders)){
            $orderDetails =$this->getOrderDetail($orders);
            
             print_r($orderDetails);
             dd('Ya termino');
        }   
    }
}
