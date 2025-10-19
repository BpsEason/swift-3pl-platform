<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PickingService;
use App\Http\Requests\OrderImportRequest;
use App\Jobs\AssignPickingTask;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $pickingService;

    public function __construct(PickingService $pickingService)
    {
        $this->pickingService = $pickingService;
    }

    public function import(OrderImportRequest $request) 
    {
        $tenantId = tenancy()->tenant->id;

        try {
            DB::beginTransaction();
            
            $order = Order::create([
                'tenant_id' => $tenantId,
                'platform_order_id' => $request->platform_order_id,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
            ]);
            
            $order->items()->createMany($request->items);
            
            $optimizedPath = $this->pickingService->createOptimizedPickingTask([$order]);
            $order->update(['status' => 'processing']);

            AssignPickingTask::dispatch($optimizedPath)->onQueue('picking_speed'); 

            DB::commit();

            return response()->json([
                'message' => '訂單已匯入並成功分派揀貨任務 (異步隊列)。',
                'order_id' => $order->id,
            ], 202);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '訂單匯入失敗: ' . $e->getMessage()], 500);
        }
    }
}
