<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // 路由對應的權限
    private function getRoutePermissions()
    {
        return collect(Route::getRoutes())->filter(function ($route) {
            return Str::startsWith($route->getName(), 'backend');
        })->filter(function ($route) {
            $abilities = [
                'index',
                'create',
                'edit',
                'destroy',
                'review',
                'preview',
                'export',
                'editable',
                'batch',
                'transfer',
                'callback',
                'daily',
                'channel_daily',
                'channel_detail',
                'total_revenue',
                'gateway_revenue',
            ];
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

    // 實際存在的權限
    private function getRealPermissions()
    {
        return Permission::all()->pluck('name')->toArray();
    }

    private function checkPermissions($permissions)
    {
        $real_permissions = $this->getRealPermissions();

        collect($permissions)->each(function($permission) use ($real_permissions) {
            if (!in_array($permission, $real_permissions)) {
                Permission::findOrCreate($permission);
            }
        });
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
            'route_permissions' => $this->getRoutePermissions(),
        ];

        return view('backend.role.create')->with($data);
    }

    public function store(Request $request)
    {
        $role = Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'web',
        ]);

        $permissions = $request->input('permission');

        $this->checkPermissions($permissions);

        $role->givePermissionTo($permissions);

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $data = [
            'role' => $role,
            'role_permissions' => $role->getAllPermissions()->pluck('name')->toArray(),
            'route_permissions' => $this->getRoutePermissions(),
        ];

        return view('backend.role.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();

        $permissions = $request->input('permission');

        $this->checkPermissions($permissions);

        $role->syncPermissions($permissions);

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        Role::destroy($id);

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
