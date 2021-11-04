<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ReportOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ReportIssueRequest;
use App\Models\ReportIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportIssueController extends Controller
{
    public function index()
    {
        $data = [
            'list' => ReportIssue::latest('sort')->paginate(),
            'report_options' => ReportOptions::STATUS_OPTIONS,
        ];

        return view('backend.report_issue.index')->with($data);
    }

    public function create()
    {
        $data = [
            'report_options' => ReportOptions::STATUS_OPTIONS,
        ];

        return view('backend.report_issue.create')->with($data);
    }

    public function store(ReportIssueRequest $request)
    {
        $post = $request->post();

        $issue = new ReportIssue;

        $issue->fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'issue' => ReportIssue::findOrFail($id),
            'report_options' => ReportOptions::STATUS_OPTIONS,
        ];

        return view('backend.report_issue.edit')->with($data);
    }

    public function update(ReportIssueRequest $request, $id)
    {
        $post = $request->post();

        $issue = ReportIssue::findOrFail($id);

        $issue->fill($post)->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $issue = ReportIssue::findOrFail($id);

        $issue->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function sort(Request $request)
    {
        $post = $request->post();

        ReportIssue::where('id', $post['pk'])->update(['sort' => $post['value']]);

        return Response::jsonSuccess(__('response.update.success'));
    }
}
