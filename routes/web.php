<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\ConfigController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap'])->name('language');

// Login / Logout
Auth::routes(['verify' => true]);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Backend iframe layout
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::post('upload/{dir?}/{id?}', [UploadController::class, 'upload'])->name('upload'); // 單檔案上傳
    Route::post('editor/upload/{dir?}/{id?}', [UploadController::class, 'editorUpload'])->name('editor.upload'); // CKEditor
});

// Backend iframe pages
Route::middleware(['auth'])->prefix('backend')->as('backend.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // 系统配置
    Route::resource('config', ConfigController::class);

    // 用户管理
    Route::resource('user', UserController::class);
    Route::put('user/{id}/block', [UserController::class,'block'])->name('user.block'); // 切换用户状态

});
