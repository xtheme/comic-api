<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function qrcode(Request $request)
    {
        $data = [
            'url' => $request->input('url'),
            'size' => $request->input('size') ?? 200,
        ];

        return view('backend.tool.qrcode')->with($data);
    }
}
