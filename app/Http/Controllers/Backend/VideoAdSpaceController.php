<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\VideoAdSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VideoAdSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $name = $request->input('name');

        $list = VideoAdSpace::where('name', 'like', '%' . $name . '%')->orderByDesc('id')->paginate();

        return view('backend.video_ad_space.index', [
            'list' => $list
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $data = VideoAdSpace::findOrFail($id);

        return view('backend.video_ad_space.edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = $request->post();

        $data = VideoAdSpace::findOrFail($id);

        $data->fill($post)->save();

        return Response::jsonSuccess('更新资料成功！');
    }
}
