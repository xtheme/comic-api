<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=RolesAndPermissionsSeeder
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->addPermissions();
        $this->addRoles();

        $this->setSuperAdmin();
        $this->setAdmin();
        $this->setContentManager();
        $this->setAdManager();
        $this->setUserManager();
    }

    /**
     * 只取後台有頁面的路由
     * @return array
     */
    protected function getBackendRoutesName()
    {
        // Permission::query()->delete();

        return collect(Route::getRoutes())->filter(function ($route) {
            return Str::startsWith($route->getName(), 'backend');
        })->filter(function ($route) {
            $abilities = ['index', 'create', 'edit', 'destroy', 'review', 'preview', 'export', 'editable', 'batch', 'transfer'];
            $name = last(explode('.', $route->getName()));
            return Str::endsWith($name, $abilities);
        })->map(function ($route) {
            return $route->getName();
        })->values()->toArray();
    }

    protected function addPermissions()
    {
        $routes = $this->getBackendRoutesName();

        collect($routes)->each(function ($route) {
            Permission::findOrCreate($route);
        });
    }

    protected function addRoles()
    {
        Role::findOrCreate('超级管理员');
        Role::findOrCreate('内容管理员');
        Role::findOrCreate('漫画管理员');
        Role::findOrCreate('广告管理员');
        Role::findOrCreate('用户管理员');
    }

    protected function setSuperAdmin()
    {
        $role = Role::findOrFail(1);
        $role->syncPermissions(Permission::all());
    }

    protected function setAdmin()
    {
        $role = Role::findOrFail(2);
        $permissions = Permission::all()->reject(function ($permission) {
            return Str::contains($permission->name, 'destroy');
        });
        $role->syncPermissions($permissions);
    }

    protected function setContentManager()
    {
        $role = Role::findOrFail(3);
        $permissions = Permission::all()->filter(function ($permission) {
            return Str::contains($permission->name, ['book', 'video', 'user', 'vip', 'report']);
        });
        $role->syncPermissions($permissions);
    }

    protected function setAdManager()
    {
        $role = Role::findOrFail(4);
        $permissions = Permission::all()->filter(function ($permission) {
            return Str::contains($permission->name, ['ad', 'ad_space']);
        });
        $role->syncPermissions($permissions);
    }

    protected function setUserManager()
    {
        $role = Role::findOrFail(5);
        $permissions = Permission::all()->filter(function ($permission) {
            return Str::contains($permission->name, ['user', 'vip']);
        });
        $role->syncPermissions($permissions);
    }
}
