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

    protected $signature = 'command:upload-order {database} {area} {closing?} {id_importation?} {type?}';
    protected $description = 'Command upload order to ERP';

    public function handle() : int
    {
        try {
            $db             = $this->argument('database');
            $area           = $this->argument('area');
            $closing        = $this->argument('closing');
            $id_importation = $this->argument('id_importation', null);
            $type           = $this->argument('type', null);
         
            $configDB = $this->connectionDB($db, 'externa', $area); 
            if($configDB != 0){
                DB::connection('mysql')->table('tbl_log')->insert([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Commands::UploadOrder[handle()] => Conexion Local: Linea '.__LINE__.'; '.$configDB,
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
                return 1;
            }
        
            if($closing == null || $closing == 'null'){
                $db = Connection_Bexsoluciones::getAll()->where('id', $db)->first();
                $closing = DB::connection($db->name)
                    ->table('tbldmovenc')
                    ->where('codtipodoc', '4')
                    ->where('estadoenviows', '0')
                    ->whereNotNull('numcierre')
                    ->min('numcierre');

                if($closing == null){
                    DB::connection('mysql')->table('tbl_log')->insert([
                        'id_table'    => $id_importation,
                        'type'        => $type,
                        'descripcion' => 'Commands::UploadOrder[handle()] => Sin numero de cierre en la BD '.$db->name,
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ]);
                    //return 1;
                }
            }
           
            $orders = $this->getOrderHeder($db->id, $area, $closing);
          
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
