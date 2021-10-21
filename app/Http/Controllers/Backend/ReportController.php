<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $list = Report::with('user', 'book', 'report_type')->orderByDesc('id')->paginate();

        return view('backend.report.index', [
            'list' => $list
        ]);
    }
}
