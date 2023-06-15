<?php

namespace App\Jobs;

use App\Enums\OrderEnum;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = new Order();
        $order = $order->where('id', $this->id);
        $order = $order->first();

        if($order){
            if(strtotime($order->expired_date) < strtotime(date("YmdHis"))){
                $order->update([
                    'status' => OrderEnum::STATUS_EXPIRED,
                ]);
            }
        }
        
    }
}
