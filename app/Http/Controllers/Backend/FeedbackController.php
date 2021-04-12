<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FeedBack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $list = FeedBack::with('user')->orderByDesc('addtime')->paginate();

        return view('backend.feedback.index', [
            'list' => $list
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feedback = FeedBack::findOrFail($id);

        $feedback->delete();

        return Response::jsonSuccess('操作成功！');
    }

    public function batchDestroy(Request $request)
    {
        FeedBack::destroy($request->post('ids'));
        return Response::jsonSuccess('删除成功！');
    }


}
