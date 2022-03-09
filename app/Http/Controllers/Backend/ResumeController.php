<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ResumeOptions;
use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ResumeController extends Controller
{
    public function index()
    {
        $data = [
            'list' => Resume::paginate(),
        ];

        return view('backend.resume.index')->with($data);
    }

    public function create()
    {
        $data = [
            'body_shape' => ResumeOptions::BODY_SHAPE,
            'service_items' => ResumeOptions::SERVICE_ITEMS,
        ];

        return view('backend.resume.create')->with($data);
    }

    public function store(Request $request)
    {
        Resume::create([
            'name' => $request->input('name'),
            'guard_name' => 'web',
        ]);

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $resume = Resume::findOrFail($id);

        $data = [
            'resume' => $resume,
        ];

        return view('backend.resume.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $resume = Resume::findOrFail($id);
        $resume->name = $request->input('name');
        $resume->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        Resume::destroy($id);

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
