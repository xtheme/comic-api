<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UpgradePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=UpgradePermissionsSeeder
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->addPermissions();

        $this->setSuperAdmin();
    }

    /**
     * 只取後台有頁面的路由
     * @return array
     */
    protected function getBackendRoutesName()
    {
        return collect(Route::getRoutes())->filter(function ($route) {
            return Str::startsWith($route->getName(), 'backend');
        })->filter(function ($route) {
            $abilities = ['index', 'create', 'edit', 'destroy', 'review', 'preview', 'export', 'editable', 'batch', 'transfer', 'unbind'];
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

    protected function setSuperAdmin()
    {
        $role = Role::findOrFail(1);
        $role->syncPermissions(Permission::all());
    }

}
