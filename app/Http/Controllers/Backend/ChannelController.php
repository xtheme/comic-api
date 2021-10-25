<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index(Request $request)
    {
        $channels = Channel::paginate(100);

        $data = [
            'list' => $channels,
        ];

        return view('backend.channel.index')->with($data);
    }

}
