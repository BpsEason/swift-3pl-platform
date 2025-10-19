<?php

use Illuminate\Support\Facades\Route;

// 這些路由在 Stancl/Tenancy 的租戶連接下運行
Route::get('/', function () {
    return "Tenant: " . tenant('id') . " - Partner Portal Dashboard is running.";
});
