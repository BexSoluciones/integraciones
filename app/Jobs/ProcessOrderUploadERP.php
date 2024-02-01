<?php

namespace App\Jobs;

use App\Custom\OrderCoreCustomGeneral;
use App\Custom\bex_0002\OrderCoreCustom as OrderCoreCustom2;
use App\Custom\bex_0003\OrderCoreCustom as OrderCoreCustom3;
use App\Custom\bex_0004\OrderCoreCustom as OrderCoreCustom4;
use App\Custom\bex_0005\OrderCoreCustom as OrderCoreCustom5;
use App\Custom\bex_0006\OrderCoreCustom as OrderCoreCustom6;
use App\Models\Tbl_Log;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessOrderUploadERP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;

    protected $order;
    protected $orderDetail;
    protected $cia;
    protected $closing;
    protected $connection_id;

    public function __construct($order, $cia, $closing, $orderDetail = null, $connection_id = null)
    {
        $this->order         = $order;
        $this->cia           = $cia;
        $this->closing       = $closing;
        $this->orderDetail   = $orderDetail;
        $this->connection_id = $connection_id;
    }

    public function handle(): void
    {  
        try{
            if($this->cia->bdlicencias == 'platafor_pi055'){
                $objOrederCore = new OrderCoreCustom2();
                $objOrederCore->uploadOrder($this->order, $this->cia, $this->closing);
            }elseif($this->cia->bdlicencias == 'platafor_pi002'){
                $objOrederCore = new OrderCoreCustom3();
                $objOrederCore->uploadOrder($this->order, $this->cia, $this->closing);  
            }elseif($this->cia->bdlicencias == 'platafor_pi151'){
                $objOrederCore = new OrderCoreCustom4();
                $objOrederCore->uploadOrder($this->order, $this->cia, $this->closing);
            }elseif($this->cia->bdlicencias == 'platafor_pi131'){
                $objOrederCore = new OrderCoreCustom5();
                $objOrederCore->uploadOrder($this->order, $this->cia, $this->closing);    
            }elseif($this->cia->bdlicencias == 'platafor_pi287'){
                $objOrederCore = new OrderCoreCustom6();
                $objOrederCore->uploadOrder($this->order, $this->cia, $this->closing, $this->connection_id); 
            }else{
                $objOrederCore = new OrderCoreCustomGeneral();
                $objOrederCore->uploadOrder($this->order, $this->orderDetail, $this->cia);    
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Jobs::ProcessOrderUploadERP[handle()] => '.$e->getMessage()
            ]);
        }
    }
}
