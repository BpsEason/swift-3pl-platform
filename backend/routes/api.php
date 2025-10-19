<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Services\ShippingService;
use App\Models\Order;
use App\Models\Tenant;

// [OMS 訂單整合] 透過 Request Data 中的 tenant_id 初始化租戶
Route::post('/orders/import', [OrderController::class, 'import'])->middleware('tenant.api');

// [WMS 庫存查詢]
Route::get('/inventory/{tenantId}/{sku}', function ($tenantId, $sku) {
    // 此處應實作手動初始化 tenancy()->initialize(Tenant::find($tenantId)); 後，查詢 App\Models\Inventory
    return response()->json(['tenant' => $tenantId, 'sku' => $sku, 'quantity' => rand(5, 50), 'status' => 'Mock Realtime']);
});

// 💡 模擬出貨 API (觸發 Webhook)
Route::post('/shipping/{tenantId}/{orderId}', function ($tenantId, $orderId, ShippingService $shippingService) {
    tenancy()->initialize(Tenant::find($tenantId));
    $order = Order::find($orderId);
    
    if (!$order) {
        tenancy()->end();
        return response()->json(['error' => 'Order not found in tenant database.'], 404);
    }
    
    try {
        $result = $shippingService->createShipment($order);
        tenancy()->end();
        return response()->json(['message' => 'Shipment created and Webhook dispatched.', 'tracking_number' => $result['tracking_number']]);
    } catch (\Exception $e) {
        tenancy()->end();
        return response()->json(['error' => 'Shipping failed: ' . $e->getMessage()], 500);
    }
})->name('api.shipping');
