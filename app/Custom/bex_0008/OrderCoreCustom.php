<?php

namespace App\Custom\bex_0006;

use App\Models\Tbl_Log;

use App\Models\LogErrorImportacionModel;
use App\Traits\ConnectionTrait;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderCoreCustom
{
    public function uploadOrder($orders, $cia, $closing)
    {
        try{
            
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Custom::bex_0006/OrderCoreCustom[uploadOrder()] => '.$e->getMessage()
            ]);
            return print '▲ Error en uploadOrder';
        }
    }
}
