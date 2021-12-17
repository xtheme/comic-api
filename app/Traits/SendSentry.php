<?php

namespace App\Traits;

use Throwable;

trait SendSentry
{
    public function failed(Throwable $exception)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }
}
