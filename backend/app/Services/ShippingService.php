<?php

namespace App\Services;

use App\Models\Order;
use App\Adapters\BlackCatAdapter;
use App\Events\ShipmentCreated; 
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected $adapters = [
        'blackcat' => BlackCatAdapter::class, 
    ];

    public function createShipment(Order $order, string $provider = 'blackcat')
    {
        if (!isset($this->adapters[$provider])) {
            throw new \InvalidArgumentException("不支持的物流供應商: {$provider}");
        }

        try {
            $response = (new $this->adapters[$provider]())->createLabel($order); 
            
            $order->update([
                'status' => 'shipped', 
                'tracking_number' => $response['tracking_number']
            ]);
            
            // 核心：發送事件，觸發異步 Webhook 監聽器
            ShipmentCreated::dispatch($order); 
            
            Log::info("已透過 {$provider} 創建 Order {$order->id} 的出貨單。Webhook 事件已分派。");

            return $response;
        } catch (\Exception $e) {
            Log::error("Order {$order->id} 出貨失敗: " . $e->getMessage());
            throw $e;
        }
    }
}
