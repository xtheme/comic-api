<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\InstallPwa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InstallController extends Controller
{
    public function pwa(Request $request)
    {
        $channel_id = $request->header('ch') ?? 1;

        InstallPwa::dispatch($channel_id);

        return Response::jsonSuccess(__('api.success'));
    }
}
