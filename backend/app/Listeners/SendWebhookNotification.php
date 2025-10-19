<?php
namespace App\Listeners;
use App\Events\ShipmentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SendWebhookNotification implements ShouldQueue
{
    public $queue = 'webhook_high_priority'; 

    public function handle(ShipmentCreated $event): void
    {
        $order = $event->order;
        $webhookUrl = 'https://partner-system.com/api/webhook/status'; 
        
        Log::info("正在為訂單 ID: {$order->id} (狀態: {$order->status}) 發送 Webhook 通知。");
        
        $response = Http::timeout(5)->post($webhookUrl, [
            'tenant_id' => $order->tenant_id,
            'platform_order_id' => $order->platform_order_id,
            'status' => $order->status,
            'tracking_number' => $order->tracking_number,
        ]);
        
        if ($response->successful()) {
            Log::info("Webhook 成功發送給合作夥伴系統，訂單 ID: {$order->id}。");
        } else {
            // 由於設置了 --tries=3，這裡會重試
            Log::error("Webhook 發送失敗，訂單 ID: {$order->id}。Status: " . $response->status());
            // throw new \Exception("Webhook failed."); // 讓任務重試
        }
    }
}
