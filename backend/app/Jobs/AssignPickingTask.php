<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AssignPickingTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $queue = 'picking_speed'; 
    public $optimizedPath;

    public function __construct(array $optimizedPath)
    {
        $this->optimizedPath = $optimizedPath;
    }

    public function handle(): void
    {
        // 模擬 WMS 將任務推送到揀貨設備
        Log::info("Picked Task Assigned to: " . $this->optimizedPath['optimized_path_id']);
        sleep(2); // 模擬處理時間
    }
}
