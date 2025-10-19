<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Inventory;
use Illuminate\Support\Facades\Artisan;

class DemoDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = '3pl_demo_co';
        $tenant = Tenant::updateOrCreate(['id' => $tenantId], ['data' => ['name' => 'Swift Demo Tenant']]);
        
        $this->command->info("Initializing Tenant DB for: {$tenantId}");
        
        // 執行租戶遷移
        $tenant->run(\function() {
            Artisan::call('migrate', [
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);
        });
        
        tenancy()->initialize($tenant); 
        
        // 創建測試數據
        Order::factory(30)->create(['tenant_id' => $tenantId])->each(function ($order) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $order->items()->create([
                    'sku' => 'SKU-' . str_pad(rand(1, 5), 3, '0', STR_PAD_LEFT),
                    'quantity' => rand(1, 5),
                ]);
            }
        });

        // 創建庫存數據
        $locations = ['A1-01', 'A1-02', 'B2-01', 'C3-05', 'D4-10'];
        for ($i = 1; $i <= 5; $i++) {
            foreach ($locations as $location) {
                Inventory::updateOrCreate(
                    ['tenant_id' => $tenantId, 'sku' => 'SKU-' . str_pad($i, 3, '0', STR_PAD_LEFT), 'location' => $location],
                    ['quantity' => rand(50, 200)]
                );
            }
        }
        
        tenancy()->end();
        $this->command->info("✅ Demo Tenant '{$tenantId}' created, Migrated, and Seeded.");
    }
}
