<?php

namespace App\Http\Controllers\Backend;

use App\Enums\DomainOptions;
use App\Http\Controllers\Controller;
use App\Models\ResourceDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ResourceDomainController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type') ?? 'wap';

        $data = [
            'type' => $type,
            'list' => ResourceDomain::latest()->paginate(),
            'type_options' => DomainOptions::TYPE_OPTIONS,
            'status_options' => DomainOptions::STATUS_OPTIONS,
        ];

        return view('backend.resource_domain.index')->with($data);
    }

    public function create()
    {
        $data = [
            'type_options' => DomainOptions::TYPE_OPTIONS,
            'status_options' => DomainOptions::STATUS_OPTIONS,
        ];

        return view('backend.resource_domain.create')->with($data);
    }

    public function store(Request $request)
    {
        $domain = new ResourceDomain;

        $domain->type = $request->input('type');
        $domain->domain = cleanDomain($request->input('domain'));
        $domain->desc = $request->input('desc');
        $domain->status = $request->input('status');

        if ($request->input('expire_at')) {
            $domain->expire_at = $request->input('expire_at') . ' 00:00:00';
        }

        if ($request->input('status') == 3) {
            $domain->intercept_at = now();
        }

        $domain->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'domain' => ResourceDomain::findOrFail($id),
            'type_options' => DomainOptions::TYPE_OPTIONS,
            'status_options' => DomainOptions::STATUS_OPTIONS,
        ];

        return view('backend.resource_domain.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $domain = ResourceDomain::findOrFail($id);

        $domain->type = $request->input('type');
        $domain->domain = cleanDomain($request->input('domain'));
        $domain->desc = $request->input('desc');
        $domain->status = $request->input('status');

        if ($request->input('expire_at')) {
            $domain->expire_at = $request->input('expire_at') . ' 00:00:00';
        }

        if ($request->input('status') == 3) {
            $domain->intercept_at = now();
        }

        $domain->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $domain = ResourceDomain::findOrFail($id);

        $domain->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
