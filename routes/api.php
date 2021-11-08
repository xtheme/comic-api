<?php

use App\Http\Controllers\Api;
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

Route::as('api.')->middleware(['api'])->group(function () {
    Route::prefix(config('api.version'))->middleware(['api.location', 'api.header', 'api.sign'])->group(function () {
        Route::prefix('bootstrap')->as('bootstrap.')->group(function () {
            Route::get('/configs', [Api\BootstrapController::class, 'configs'])->name('configs');
            Route::get('/notices', [Api\BootstrapController::class, 'notices'])->name('notices');
        });

        Route::prefix('auth')->as('auth.')->group(function () {
            Route::post('/captcha', [Api\AuthController::class, 'captcha'])->name('captcha');
            Route::post('/login', [Api\AuthController::class, 'login'])->name('login');
            Route::post('/register', [Api\AuthController::class, 'register'])->name('register');
        });

        // 首頁:導航列
        Route::prefix('navigation')->as('navigation.')->group(function () {
            Route::get('/', [Api\NavigationController::class , 'list'])->name('list');
        });

        // 首頁:主题
        Route::prefix('topic')->as('topic.')->group(function () {
            Route::get('/{type}', [Api\TopicController::class, 'list'])->name('list');
            Route::get('/filter/{filter_id}/{page?}', [Api\TopicController::class, 'filter'])->name('filter');
        });

        // 視頻
        Route::prefix('movie')->as('movie.')->group(function () {
            Route::get('/list/{type}', [Api\MovieController::class, 'list'])->name('list'); // 最新 / 熱門 / (隨機)推薦
            Route::get('/detail/{id}', [Api\MovieController::class, 'detail'])->name('detail');
        });

        // 樓鳳履歷
        Route::prefix('resume')->as('resume.')->group(function () {
            Route::get('/cities', [Api\ResumeController::class, 'cities'])->name('cities');
            Route::get('/list/{city?}', [Api\ResumeController::class, 'list'])->name('list');
            Route::get('/detail/{id}', [Api\ResumeController::class, 'detail'])->name('detail');
        });

        // 分類頁
        Route::prefix('category')->as('category.')->group(function () {
            Route::get('/tags/{type?}', [Api\CategoryController::class, 'tags'])->name('tags');
            Route::post('/search', [Api\CategoryController::class, 'search'])->name('search');
        });

        // 漫畫
        Route::prefix('book')->as('book.')->group(function () {
            Route::get('/{id}', [Api\BookController::class, 'detail'])->name('detail');
            Route::get('/chapter/{chapter_id}', [Api\BookController::class, 'chapter'])->name('chapter');
            Route::post('/report/{type_id}/{id}', [Api\ReportController::class, 'report'])->name('report');
        });

        // 排行榜
        Route::prefix('ranking')->as('ranking.')->group(function () {
            Route::get('/day/{type?}', [Api\RankingController::class, 'day'])->name('day');
            Route::get('/week/{type?}', [Api\RankingController::class, 'week'])->name('week');
            Route::get('/month/{type?}', [Api\RankingController::class, 'month'])->name('month');
            Route::get('/year/{type?}', [Api\RankingController::class, 'year'])->name('year');
            Route::get('/japan/{type?}', [Api\RankingController::class, 'japan'])->name('japan');
            Route::get('/korea/{type?}', [Api\RankingController::class, 'korea'])->name('korea');
            Route::get('/latest/{type?}', [Api\RankingController::class, 'latest'])->name('latest');
        });

        // 客服
        Route::prefix('service')->as('service.')->group(function () {
            Route::get('/url', [Api\ServiceController::class, 'url'])->name('url');
        });

        // 广告 (避開 ad 字眼避免被封鎖)
        Route::prefix('notable')->as('notable.')->group(function () {
            Route::get('/space/{id}', [Api\AdController::class, 'space'])->name('space');
        });
    });

    // 需要 Bearer Token (sanctum 簽發)
    Route::prefix(config('api.version'))->middleware(['api.header', 'api.sign', 'auth:sanctum'])->group(function () {
        // 用戶驗證
        Route::prefix('auth')->as('auth.')->group(function () {
            Route::any('/profile', [Api\AuthController::class, 'profile'])->name('profile');
            Route::any('/logout', [Api\AuthController::class, 'logout'])->name('logout');
            Route::any('/refresh', [Api\AuthController::class, 'refresh'])->name('refresh');
            Route::post('/modify', [Api\AuthController::class, 'modify'])->name('modify');
        });

        // 用戶交易紀錄
        Route::prefix('user')->as('user.')->group(function () {
            Route::post('/order', [Api\UserController::class, 'order'])->name('order');
            Route::post('/recharge', [Api\UserController::class, 'recharge'])->name('recharge');
            Route::post('/purchase', [Api\UserController::class, 'purchase'])->name('purchase');
        });

        // 用戶訪問紀錄
        Route::prefix('visit')->as('visit.')->group(function () {
            Route::post('/list', [Api\VisitController::class, 'list'])->name('list');
            Route::post('/destroy', [Api\VisitController::class, 'destroy'])->name('destroy');
        });

        // 用戶收藏紀錄
        Route::prefix('favorite')->as('favorite.')->group(function () {
            Route::post('/list', [Api\FavoriteController::class, 'list'])->name('list');
            Route::post('/save', [Api\FavoriteController::class, 'save'])->name('save');
            Route::post('/destroy', [Api\FavoriteController::class, 'destroy'])->name('destroy');
        });

        // 支付中心
        Route::prefix('payment')->as('payment.')->group(function () {
            Route::get('/pricing', [Api\PaymentController::class, 'pricing'])->name('pricing'); // 支付方案
            Route::get('/gateway/{pricing_id}', [Api\PaymentController::class, 'gateway'])->name('gateway'); // 支付渠道
            Route::post('/pay', [Api\PaymentController::class, 'pay'])->name('pay'); // 調用渠道支付
        });

        // 購買商品
        Route::post('/purchase', [Api\PurchaseController::class, 'purchase'])->name('purchase');
    });

    // 第三方支付回調
    Route::prefix('payment')->as('payment.')->group(function () {
        Route::any('/callback', [Api\PaymentController::class, 'callback'])->name('callback'); // 支付結果回調
        Route::any('/notify/{order_no?}', [Api\PaymentController::class, 'notify'])->name('notify'); // 支付結果回調
        Route::any('/mockCallback', [Api\PaymentController::class, 'mockCallback']); // 測試接口:支付結果回調
    });
});

// 路由不存在时返回 json error
Route::fallback(function () {
    return response()->json([
        'code' => 200,
        'msg'  => 'Route Not Found!',
    ], 404);
});

/*
// 簡訊
Route::prefix('sms')->as('sms.')->group(function () {
    Route::post('/verify', [Api\SmsController::class, 'verify'])->name('verify'); // 校验SSO
    Route::post('/send', [Api\SmsController::class, 'send'])->name('send');
});

// 动画
Route::prefix('video')->as('video.')->group(function () {
    Route::get('/list/{page?}', [Api\VideoController::class, 'list'])->name('list');
    Route::get('/detail/{id}', [Api\VideoController::class, 'detail'])->name('detail');
    Route::get('/recommend/{id?}', [Api\VideoController::class, 'recommend'])->name('recommend');
    Route::post('/play/{id}/{series_id}', [Api\VideoController::class, 'play'])->name('play');
});

// 评论
Route::prefix('comment')->as('comment.')->group(function () {
    Route::get('/list/{chapter_id}/{order}', [Api\CommentController::class, 'list'])->name('list');
    Route::post('/add', [Api\CommentController::class, 'add'])->name('add');
    Route::post('/like/{comment_id}', [Api\CommentController::class, 'like'])->name('like');
    Route::post('/destroy/{comment_id}', [Api\CommentController::class, 'destroy'])->name('destroy');
});

*/
