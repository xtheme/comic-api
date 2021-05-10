<?php

use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\SignController;
use App\Http\Controllers\Api\SmsController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix(config('api.version'))->as('api.')->middleware(['api.header', 'api.sign', 'jwt.token'])->group(function () {

    Route::prefix('user')->as('user.')->group(function () {
        Route::post('/device', [UserController::class, 'device'])->name('device');
        Route::post('/mobile', [UserController::class, 'mobile'])->name('mobile')->middleware('sso');
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');
        Route::post('/modify', [UserController::class, 'modify'])->name('modify');
        Route::post('/avatar', [UserController::class, 'avatar'])->name('avatar');
        Route::get('/signDetail', [SignController::class, 'signDetail'])->name('signDetail');
        Route::post('/sign', [SignController::class, 'sign'])->name('sign');
    });

    Route::prefix('sms')->as('sms.')->group(function () {
        Route::post('/send', [SmsController::class, 'send'])->name('send');
    });

    Route::prefix('ad')->as('ad.')->group(function () {
        Route::get('/space/{id}', [AdController::class, 'space'])->name('space');
    });

    // 主题区块
    Route::prefix('topic')->as('topic.')->group(function () {
        Route::get('/{causer}', [TopicController::class, 'list'])->name('list');
        Route::get('/more/{topic}/{page?}', [TopicController::class, 'more'])->name('more');
    });

    Route::prefix('video')->as('video.')->group(function () {
        Route::get('/list/{page?}', [VideoController::class, 'list'])->name('list');
        Route::get('/detail/{id}', [VideoController::class, 'detail'])->name('detail');
        Route::get('/recommend/{limit?}', [VideoController::class, 'recommend'])->name('recommend');
    });
});

Route::fallback(function () {
    return response()->json([
        'code' => 200,
        'msg'  => 'Route Not Found!',
    ], 404);
});
