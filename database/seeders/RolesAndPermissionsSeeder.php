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
    }

    protected function getBackendRoutesName()
    {
        return collect(Route::getRoutes())->filter(function ($route) {
            return Str::startsWith($route->getName(), 'backend');
        })->reject(function ($route) {
            return Str::is('backend.dashboard', $route->getName());
        })->map(function ($route) {
            return Str::replaceFirst('backend.', '', $route->getName());
        });
    }

    protected function addPermissions()
    {
        $routes = $this->getBackendRoutesName();

        $routes->each(function ($route) {
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
}
