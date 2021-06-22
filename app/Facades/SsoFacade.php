<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static checkUser($user)
 * @method static checkPhone($phone)
 * @method static exist($phone)
 * @method static destroy($phone)
 */
class SsoFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SsoService';
    }
}
