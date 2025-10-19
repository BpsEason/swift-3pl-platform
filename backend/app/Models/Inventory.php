<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Inventory extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'sku', 'location', 'quantity',
    ];

    /**
     * 儲位分區邏輯: 根據 location 的第一個字母判斷區域
     */
    public function getZoneAttribute(): string
    {
        return substr($this->location, 0, 1) . ' Zone';
    }
}
