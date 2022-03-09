<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Resume;

class ResumeController extends Controller
{
    public function index()
    {
        $data = [
            'list' => Resume::paginate(),
        ];

        return view('backend.resume.index')->with($data);
    }
}
