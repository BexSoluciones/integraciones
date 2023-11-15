<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Custom\OrderCoreCustom;
use App\Custom\OrderCoreFyelCustom;

class ProcessOrderUploadERP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    
    protected $orderDetail;

    protected $cia;

    public function __construct($order,$cia,$orderDetail = null)
    {
        $this->order= $order;
        $this->cia = $cia;
        $this->orderDetail= $orderDetail;
    }


    public function handle(): void
    {
        // Log::info('=========imprimiendo datos recibidos al job=====');
        // Log::info($this->order);
        // Log::info($this->orderDetail);
        
        if($this->cia->bdlicencias = 'platafor_pi055'){
            $objOrederCore=new OrderCoreFyelCustom();
            $objOrederCore->uploadOrder($this->order,$this->cia); 
        }else{
            $objOrederCore=new OrderCoreCustom();
            $objOrederCore->uploadOrder($this->order,$this->orderDetail,$this->cia);    
        }

    }
}
