<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = Channel::paginate(100);

        $data = [
            'list' => $channels,
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

        $category = new Channel;

        $category->fill($post)->save();

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
        $category = Channel::findOrFail($id);

        $category->update($request->input());

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $category = Channel::findOrFail($id);

        $category->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
