<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('sku')->index();
            $table->string('location')->index()->comment('儲位/貨架號');
            $table->integer('quantity');
            $table->timestamps();
            $table->unique(['sku', 'location']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
