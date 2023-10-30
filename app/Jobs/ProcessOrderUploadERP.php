<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Custom\OrderCoreCustom;

class ProcessOrderUploadERP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    
    protected $orderDetail;

    protected $cia;

    public function __construct($order,$orderDetail,$cia)
    {
        $this->order= $order;
        $this->orderDetail= $orderDetail;
        $this->cia = $cia;
    }


    public function handle(): void
    {
        // Log::info('=========imprimiendo datos recibidos al job=====');
        // Log::info($this->order);
        // Log::info($this->orderDetail);
        $objOrederCore=new OrderCoreCustom();
        $objOrederCore->uploadOrder($this->order,$this->orderDetail,$this->cia);
    }
}
