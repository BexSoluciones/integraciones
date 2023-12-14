<?php

namespace App\Console\Commands;

use App\Models\Tbl_Log;
use App\Traits\GetOrderTrait;
use App\Traits\ConnectionTrait;
use Illuminate\Support\Facades\Log;

use Illuminate\Console\Command;

class UploadOrder extends Command
{
    use ConnectionTrait, GetOrderTrait;

    protected $signature = 'command:upload-order {database} {area} {closing}';
    protected $description = 'Command upload order to ERP';

    public function handle() : int
    {
        try {
            $db      = $this->argument('database');
            $area    = $this->argument('area');
            $closing = $this->argument('closing');

            $configDB = $this->connectionDB($db, 'externa', $area); 
            if($configDB == false){
                return 0;
            }
          
            $orders = $this->getOrderHeder($db, $area, $closing);
            if($orders == true){
                return 0;
            }
           
            if(!empty($orders)){
                $orderDetails = $this->getOrderDetail($orders);
                return 0;
            }   
            return 1;
        }catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Commands::UploadOrder[handle()] => '.$e->getMessage()
            ]);
        }
    }
}
