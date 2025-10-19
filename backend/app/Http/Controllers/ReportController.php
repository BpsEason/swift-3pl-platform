<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Exports\OrdersExport; 
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; // 假設已安裝

/**
 * 報表與分析模組：處理訂單導出和效率分析 (運行在 Landlord 環境)。
 */
class ReportController extends Controller
{
    /**
     * 導出租戶訂單到 Excel 檔案。
     */
    public function exportOrders(string $tenantId)
    {
        try {
            tenancy()->initialize(Tenant::find($tenantId));
            
            $filename = "Orders_Report_{$tenantId}_" . now()->format('Ymd_His') . ".xlsx";
            
            // return Excel::download(new OrdersExport(), $filename); // 實際操作
            
            tenancy()->end();

            return response()->json([
                'message' => '報表導出請求已成功處理。',
                'tenant' => $tenantId,
                'filename' => $filename,
                'status' => 'Mock File Ready'
            ]);

        } catch (\Exception $e) {
            tenancy()->end();
            return response()->json(['error' => '報表生成失敗: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 獲取揀貨效率數據。
     */
    public function getPickingEfficiency(string $tenantId)
    {
        return response()->json([
            'metrics' => [
                'total_orders' => 1000,
                'picking_tasks_completed' => 980,
            ],
            'chart_data' => [
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                'datasets' => [['label' => '平均揀貨時間 (秒)', 'data' => [160, 155, 145, 150, 140]]]
            ]
        ]);
    }
}
