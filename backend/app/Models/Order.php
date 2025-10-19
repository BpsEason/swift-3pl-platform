<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant; 

class Order extends Model
{
    use HasFactory, BelongsToTenant; 
    protected $fillable = [
        'tenant_id', 'platform_order_id', 'status', 
        'shipping_address', 'tracking_number',
    ];
    public function items() { return $this->hasMany(OrderItem::class); }
}
