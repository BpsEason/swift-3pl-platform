<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return "<h1>Swift 3PL Landlord Application is running.</h1><p>Frontend SPA placeholder.</p><p>Try importing an Order: POST /api/orders/import with data.</p><p>Try Report: <a href='/tenant/3pl_demo_co/report/efficiency'>Efficiency Analysis</a></p>";
});

// [報表分析] - Landlord 路由下訪問租戶資料 (Landlord 系統的數據分析頁面)
Route::prefix('tenant/{tenant}')->group(function () {
    Route::get('/report/excel', [ReportController::class, 'exportOrders']);
    Route::get('/report/efficiency', [ReportController::class, 'getPickingEfficiency']);
});
