<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PickingService
{
    public function createOptimizedPickingTask(array $orders): array
    {
        $orderIds = Collection::make($orders)->pluck('id')->toArray();
        $tenantId = $orders[0]->tenant_id ?? 'N/A';

        // 模擬路徑優化: usort + zone/coord
        usort($orders, function($a, $b) {
            return $a->shipping_address <=> $b->shipping_address; 
        });
        
        $path = "Optimized-Path-T{$tenantId}-O" . implode('-', $orderIds);
        
        Log::info("Picking path generated for orders: " . implode(', ', $orderIds));

        return [
            'tenant_id' => $tenantId,
            'orders' => $orderIds,
            'optimized_path_id' => $path,
            'estimated_time_seconds' => rand(120, 300)
        ];
    }
}
