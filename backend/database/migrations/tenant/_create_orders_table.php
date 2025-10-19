<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('platform_order_id')->unique();
            $table->enum('status', ['pending', 'processing', 'picking', 'shipped', 'cancelled'])->default('pending');
            $table->text('shipping_address');
            $table->string('tracking_number')->nullable()->comment('物流追蹤碼');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
