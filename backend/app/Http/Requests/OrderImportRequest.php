<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 應在此處加入 Sanctum 授權檢查
        return true; 
    }

    public function rules(): array
    {
        // 'unique:tenant.orders,platform_order_id' 確保在當前租戶的 orders 表中唯一
        return [
            'tenant_id' => ['required', 'string', 'exists:tenants,id'],
            'platform_order_id' => ['required', 'string', 'max:255', 'unique:tenant.orders,platform_order_id'],
            'shipping_address' => ['required', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sku' => ['required', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
