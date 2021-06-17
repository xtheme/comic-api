<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FeedBack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FeedbackController extends Controller
{
    public function index()
    {
        $list = FeedBack::with('user')->orderByDesc('addtime')->paginate();

        return view('backend.feedback.index', [
            'list' => $list
        ]);
    }

    public function destroy($id)
    {
        $feedback = FeedBack::findOrFail($id);

        $feedback->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function batchDestroy(Request $request)
    {
        FeedBack::destroy($request->post('ids'));

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
