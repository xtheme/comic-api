<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response;

class DevController extends Controller
{
    /**
     * 查询廣告位底下的廣告列表
     */
    public function decrypt(Request $request)
    {
        $encrypted = $request->getContent();
        $decrypted = Crypt::decryptString($encrypted);
        // parse_str($decrypted, $params);
        $params = json_decode($decrypted, true);
        $request->replace($params);
        return Response::json($request->input());
    }
}
