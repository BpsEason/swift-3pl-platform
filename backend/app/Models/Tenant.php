<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithFeatures;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithFeatures
{
    use HasDatabase, HasDomains;
    
    // 設定租戶資料庫遷移路徑
    protected static function getTenantMigrationsPath(): ?string
    {
        return database_path('migrations/tenant');
    }

    protected $fillable = ['id', 'data'];
}
