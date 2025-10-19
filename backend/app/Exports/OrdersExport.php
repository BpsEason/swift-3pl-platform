<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        // 確保 tenant 環境已初始化
        return Order::select('id', 'platform_order_id', 'status', 'shipping_address', 'tracking_number', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID', '平台訂單號', '狀態', '收貨地址', '追蹤碼', '建立時間',
        ];
    }
}
