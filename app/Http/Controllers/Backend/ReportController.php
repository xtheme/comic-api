<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $data = [
            'list' => Report::with('user')->orderByDesc('id')->paginate(),
        ];

        return view('backend.report.index')->with($data);
    }
}
