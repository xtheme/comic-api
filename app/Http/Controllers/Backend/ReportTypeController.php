<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ReportTypeRequest;
use App\Models\BookReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ReportTypeController extends Controller
{
    public function index()
    {
        $list = BookReportType::with('admin')->orderByDesc('id')->paginate();

        return view('backend.report_type.index', [
            'list' => $list
        ]);
    }

    public function create()
    {
        return view('backend.report_type.create');
    }

    public function store(ReportTypeRequest $request)
    {
        $post = $request->post();

        $bookReportType = new BookReportType;

        $post['operator_id'] = Auth::user()->id;

        $bookReportType->fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = BookReportType::findOrFail($id);

        return view('backend.report_type.edit', [
            'data' => $data
        ]);
    }

    public function update(ReportTypeRequest $request, $id)
    {
        $post = $request->post();

        $bookReportType = BookReportType::findOrFail($id);

        $post['operator_id'] = Auth::user()->id;

        $bookReportType->fill($post)->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $bookReportType = BookReportType::findOrFail($id);

        $bookReportType->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function sort(Request $request)
    {
        $post = $request->post();

        BookReportType::where('id', $post['pk'])->update(['sort' => $post['value']]);

        return Response::jsonSuccess(__('response.update.success'));
    }
}
