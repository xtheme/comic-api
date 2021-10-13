<?php

namespace App\Gateways\Contracts;

use App\Models\Order;
use App\Models\Pricing;

interface GatewayInterface
{
    public function pay(Pricing $plan);

    public function getSign(array $params);

    public function checkSign($params);

    public function updateOrder(Order $order, array $params);

    public function mockCallback(Order $order);
}
