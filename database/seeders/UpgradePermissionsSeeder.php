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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->addRoles();

        $this->addPermissions();

        $this->setSuperAdmin();
    }

    /**
     * 建立角色
     */
    protected function addRoles()
    {
        Role::create(['name' => '管理员']);
        Role::create(['name' => '运营']);
        Role::create(['name' => '客服']);
        Role::create(['name' => '财务']);
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
                'daily',
                'revenue',
            ];
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
