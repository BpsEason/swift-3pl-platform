<?php

namespace App\Adapters;

use App\Models\Order;

class BlackCatAdapter
{
    public function createLabel(Order $order): array
    {
        $trackingNumber = 'BC-' . date('Ymd') . rand(100000, 999999);
        return [
            'success' => true,
            'tracking_number' => $trackingNumber,
            'provider' => 'BlackCat',
        ];
    }
}
