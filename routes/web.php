<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Backend\DashboardController;
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
});

// Backend iframe pages
Route::middleware(['auth'])->prefix('backend')->as('backend.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});
