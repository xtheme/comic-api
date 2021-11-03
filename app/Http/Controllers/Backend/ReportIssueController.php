<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ReportIssueRequest;
use App\Models\ReportIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ReportIssueController extends Controller
{
    public function index()
    {
        $list = ReportIssue::with('admin')->orderByDesc('id')->paginate();

        return view('backend.report_issue.index', [
            'list' => $list
        ]);
    }

    public function create()
    {
        return view('backend.report_issue.create');
    }

    public function store(ReportIssueRequest $request)
    {
        $post = $request->post();

        $bookReportType = new ReportIssue;

        $bookReportType->fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = ReportIssue::findOrFail($id);

        return view('backend.report_issue.edit', [
            'data' => $data
        ]);
    }

    public function update(ReportIssueRequest $request, $id)
    {
        $post = $request->post();

        $bookReportType = ReportIssue::findOrFail($id);

        $bookReportType->fill($post)->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $bookReportType = ReportIssue::findOrFail($id);

        $bookReportType->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function sort(Request $request)
    {
        $post = $request->post();

        ReportIssue::where('id', $post['pk'])->update(['sort' => $post['value']]);

        return Response::jsonSuccess(__('response.update.success'));
    }
}
