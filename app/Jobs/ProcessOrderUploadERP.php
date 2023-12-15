<?php

namespace App\Jobs;

use App\Custom\OrderCoreCustomGeneral;
use App\Custom\bex_0002\OrderCoreCustom;
use App\Models\Tbl_Log;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessOrderUploadERP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    protected $order;
    protected $orderDetail;
    protected $cia;
    protected $closing;

    public function __construct($order, $cia, $closing, $orderDetail = null)
    {
        $this->order= $order;
        $this->cia = $cia;
        $this->closing = $closing;
        $this->orderDetail= $orderDetail;
    }

    public function handle(): void
    {
        try{
            if($this->cia->bdlicencias == 'platafor_pi055'){
                $objOrederCore = new OrderCoreCustom();
                $objOrederCore->uploadOrder($this->order, $this->cia, $this->closing); 
            }else{
                $objOrederCore = new OrderCoreCustomGeneral();
                $objOrederCore->uploadOrder($this->order, $this->orderDetail, $this->cia);    
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Custom::OrderCoreCustom[uploadOrder()] => '.$e->getMessage()
            ]);
        }
    }
}
