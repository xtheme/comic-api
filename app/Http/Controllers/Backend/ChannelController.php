<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\ChannelDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ChannelController extends Controller
{
    public function index()
    {
        $select = [
            'id',
            'description',
            'safe_landing',
            'register_count',
            'recharge_count',
            'recharge_amount',
            DB::raw('(wap_new_amount + wap_renew_amount) as wap_amount'),
            DB::raw('(app_new_amount + app_renew_amount) as app_amount'),
            'app_download_count',
            'pwa_download_count',
        ];

        $channels = Channel::select($select)->latest('recharge_amount')->paginate(50);

        // 推廣域名
        $domains = ChannelDomain::where('type', 'wap')->where('status', 1)->get();

        $data = [
            'list' => $channels,
            'domains' => $domains,
        ];

        return view('backend.channel.index')->with($data);
    }

    public function create()
    {
        return view('backend.channel.create');
    }

    public function store(Request $request)
    {
        $post = $request->post();

        $channel = new Channel;

        $channel->fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'channel' => Channel::findOrFail($id),
        ];

        return view('backend.channel.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $channel = Channel::findOrFail($id);

        $channel->update($request->input());

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $channel = Channel::findOrFail($id);

        $channel->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
