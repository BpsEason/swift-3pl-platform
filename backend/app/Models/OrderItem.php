<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class OrderItem extends Model
{
    use HasFactory, BelongsToTenant;
    protected $fillable = ['order_id', 'sku', 'quantity'];
    public function order() { return $this->belongsTo(Order::class); }
}
