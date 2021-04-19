<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookReport;
use Illuminate\Http\Request;

class ComicReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = BookReport::with('user' , 'book' , 'report_type')->orderByDesc('id')->paginate();

        return view('backend.bookreport.index', [
            'list' => $list
        ]);
    }
}
