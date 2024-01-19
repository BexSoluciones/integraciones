<?php

namespace App\Console\Commands;

use App\Models\Tbl_Log;
use App\Models\Connection_Bexsoluciones;
use App\Traits\GetOrderTrait;
use App\Traits\ConnectionTrait;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


use Illuminate\Console\Command;

class UploadOrder extends Command
{
    use ConnectionTrait, GetOrderTrait;

    protected $signature = 'command:upload-order {database} {area} {closing?}';
    protected $description = 'Command upload order to ERP';

    public function handle() : int
    {
        try {
            $db      = $this->argument('database');
            $area    = $this->argument('area');
            $closing = $this->argument('closing');
         
            $configDB = $this->connectionDB($db, 'externa', $area); 
            if($configDB != 0){
                DB::connection('mysql')->table('tbl_log')->insert([
                    'descripcion' => 'Commands::UploadOrder[handle()] => Conexion Local: Linea '.__LINE__.'; '.$configDB,
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
                return 1;
            }
       
            if($closing == null){
                $db = Connection_Bexsoluciones::getAll()->where('id', $db)->value('name');
                $closing = DB::connection($db)
                    ->table('tbldmovenc')
                    ->where('codtipodoc', 4)
                    ->whereNotNull('numcierre')
                    ->min('numcierre');

                if($closing == null){
                    DB::connection('mysql')->table('tbl_log')->insert([
                        'descripcion' => 'Commands::UploadOrder[handle()] => Sin numero de cierre en la BD '.$db,
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ]);
                    return 1;
                }
            }
          
            $orders = $this->getOrderHeder($db, $area, $closing);
          
            if($orders == 0){
                return 0;
            }
       
            if(!empty($orders)){
                $orderDetails = $this->getOrderDetail($orders);
                return 0;
            }   
            return 1;
        }catch (\Exception $e) {
            DB::connection('mysql')->table('tbl_log')->insert([
                'descripcion' => 'Commands::UploadOrder[handle()] => '.$e->getMessage(),
                'created_at'  => now(),
                'updated_at'  => now()
            ]);
            return 1;
        }
    }
}
