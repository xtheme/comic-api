<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static from($type)
 * @method static visit($target_id)
 * @method static play($parent_id, $target_id)
 */
class RecordFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'RecordService';
    }
}
