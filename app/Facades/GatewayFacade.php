<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getDailyLimit($payment_id)
 * @method static incDailyLimit($payment_id, $amount)
 */
class GatewayFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GatewayService';
    }
}
