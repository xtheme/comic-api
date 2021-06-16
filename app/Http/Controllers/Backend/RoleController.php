<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private function getPermissions()
    {
        return collect(Route::getRoutes())->filter(function ($route) {
            return Str::startsWith($route->getName(), 'backend');
        })->filter(function ($route) {
            $abilities = ['index', 'create', 'edit', 'destroy', 'review', 'preview', 'export', 'editable', 'batch', 'transfer'];
            $name = last(explode('.', $route->getName()));
            return Str::endsWith($name, $abilities);
        })->mapToGroups(function ($route) {
            $arr = explode('.', $route->getName());
            return [$arr[1] => [
                'route' => $route->getName(),
                'name' => __('permissions.' . $arr[2]) . __('permissions.' . $arr[1]),
            ]];
        })->sortBy('route')->toArray();
    }

    public function index()
    {
        $data = [
            'list' => Role::paginate(),
        ];

        return view('backend.role.index')->with($data);
    }

    public function create()
    {
        $data = [
            'permissions' => $this->getPermissions(),
        ];

        return view('backend.role.create')->with($data);
    }

    public function store(Request $request)
    {
        $role = Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'web',
        ]);

        $role->givePermissionTo($request->input('permission'));

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $data = [
            'role' => $role,
            'role_permissions' => $role->getAllPermissions()->pluck('name')->toArray(),
            'permissions' => $this->getPermissions(),
        ];

        return view('backend.role.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        Role::destroy($id);

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
