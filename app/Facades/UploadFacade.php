<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static unsync()
 * @method static to($path, $id = null)
 * @method static store($file, $path)
 */
class UploadFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UploadService';
    }
}
