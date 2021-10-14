<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\BookReport;
use Illuminate\Http\Request;
use App\Models\BookReportType;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{

    public function report(Request $request , $type_id , $id)
    {

        $book = Book::find($id);

        if (!$book) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        $report_type = BookReportType::find($type_id);

        if (!$report_type) {
            return Response::jsonError('举报类型不存在或已下架！');
        }
        
        $data = [
            'user_id' => $request->user()->id,
            'book_id' => $id,
            'book_report_type_id' => $type_id,
            'created_at' => Carbon::now(),
        ];

        BookReport::insert($data);

        return Response::jsonSuccess(__('api.success'), []);
    }


}
