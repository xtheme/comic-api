<?php

use App\Http\Controllers\Api;
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

Route::middleware(['api'])->as('api.')->group(function () {

    Route::get('stress', [Api\DevController::class, 'stress']);
    Route::get('decrypt', [Api\DevController::class, 'decrypt']);

    // 第三方支付回調
    Route::prefix('payment')->as('payment.')->group(function () {
        // Route::any('/callback', [Api\PaymentController::class, 'callback']); // 支付結果回調
        Route::any('notify/{order_no?}', [Api\PaymentController::class, 'notify'])->name('notify');
        Route::any('mockCallback', [Api\PaymentController::class, 'mockCallback']); // 測試接口:支付結果回調
    });

    Route::prefix(config('api.version'))->middleware(['request.decrypt', 'api.location', 'api.header', 'api.sign'])->group(function () {
        Route::prefix('bootstrap')->group(function () {
            Route::get('configs', [Api\BootstrapController::class, 'configs']);
            Route::get('notices', [Api\BootstrapController::class, 'notices']);
        });

        Route::prefix('auth')->group(function () {
            Route::post('captcha', [Api\AuthController::class, 'captcha']);
            Route::post('login', [Api\AuthController::class, 'login']);
            Route::post('register', [Api\AuthController::class, 'register']);
            Route::post('fast/register', [Api\AuthController::class, 'fastRegister']);
        });

        // 首頁:導航列
        Route::prefix('navigation')->group(function () {
            Route::get('/', [Api\NavigationController::class, 'list']);
        });

        // 首頁:主题
        Route::prefix('topic')->group(function () {
            Route::get('{type}', [Api\TopicController::class, 'list']);
            Route::get('filter/{filter_id}/{page?}', [Api\TopicController::class, 'filter']);
        });

        // 视频
        Route::prefix('video')->group(function () {
            Route::get('recommend/{id?}', [Api\VideoController::class, 'recommend']);
            Route::get('detail/{id}', [Api\VideoController::class, 'detail']);
            Route::get('play/{id}', [Api\VideoController::class, 'play']);
        });

        // 樓鳳履歷
        Route::prefix('resume')->group(function () {
            Route::get('provinces', [Api\ResumeController::class, 'provinces']);
            Route::get('cities/{province_id?}', [Api\ResumeController::class, 'cities']);
            Route::get('areas/{city_id?}', [Api\ResumeController::class, 'areas']);
            Route::get('keywords', [Api\ResumeController::class, 'keywords']);
            Route::post('list', [Api\ResumeController::class, 'list']);
            Route::get('detail/{id}', [Api\ResumeController::class, 'detail']);
        });

        // 分類頁
        Route::prefix('category')->group(function () {
            Route::get('tags/{type?}', [Api\CategoryController::class, 'tags']);
            Route::post('search', [Api\CategoryController::class, 'search']);
        });

        // 漫畫
        Route::prefix('book')->group(function () {
            Route::get('detail/{id}', [Api\BookController::class, 'detail']);
            Route::get('chapter/{chapter_id}', [Api\BookController::class, 'chapter']);
            Route::post('report/{type_id}/{id}', [Api\ReportController::class, 'report']);
        });

        // 排行榜
        Route::prefix('ranking')->group(function () {
            Route::get('day/{type?}', [Api\RankingController::class, 'day']);
            Route::get('week/{type?}', [Api\RankingController::class, 'week']);
            Route::get('month/{type?}', [Api\RankingController::class, 'month']);
            Route::get('year/{type?}', [Api\RankingController::class, 'year']);
            Route::get('japan/{type?}', [Api\RankingController::class, 'japan']);
            Route::get('korea/{type?}', [Api\RankingController::class, 'korea']);
            Route::get('latest/{type?}', [Api\RankingController::class, 'latest']);
        });

        // 客服
        Route::prefix('service')->group(function () {
            Route::get('url', [Api\ServiceController::class, 'url']);
        });

        // 广告 (避開 ad 字眼避免被封鎖)
        Route::prefix('notable')->group(function () {
            Route::get('space/{id}', [Api\AdController::class, 'space']);
        });

        // 安裝 APK/PWA
        Route::prefix('install')->group(function () {
            Route::get('pwa', [Api\InstallController::class, 'pwa']);
        });

        // 用戶反饋
        Route::prefix('feedback')->group(function () {
            Route::get('/', [Api\FeedbackController::class, 'questionnaire']);
            Route::post('add', [Api\FeedbackController::class, 'add']);
        });
    });

    // 需要 Bearer Token (sanctum 簽發)
    Route::prefix(config('api.version'))->middleware(['request.decrypt', 'api.header', 'api.sign', 'auth:sanctum'])->group(function () {
        // 用戶驗證
        Route::prefix('auth')->group(function () {
            Route::any('profile', [Api\AuthController::class, 'profile']);
            Route::any('logout', [Api\AuthController::class, 'logout']);
            Route::any('refresh', [Api\AuthController::class, 'refresh']);
            Route::post('modify', [Api\AuthController::class, 'modify']);
        });

        // 用戶交易紀錄
        Route::prefix('user')->group(function () {
            Route::post('order', [Api\UserController::class, 'order']);
            Route::post('recharge', [Api\UserController::class, 'recharge']);
            Route::post('purchase', [Api\UserController::class, 'purchase']);
        });

        // 用戶訪問紀錄
        Route::prefix('visit')->group(function () {
            Route::post('list', [Api\VisitController::class, 'list']);
            Route::post('destroy', [Api\VisitController::class, 'destroy']);
        });

        // 用戶收藏紀錄
        Route::prefix('favorite')->group(function () {
            Route::post('list', [Api\FavoriteController::class, 'list']);
            Route::post('save', [Api\FavoriteController::class, 'save']);
            Route::post('destroy', [Api\FavoriteController::class, 'destroy']);
        });

        // 支付中心
        Route::prefix('payment')->group(function () {
            Route::get('pricing', [Api\PaymentController::class, 'pricing']); // 支付方案
            Route::get('gateway/{pricing_id}', [Api\PaymentController::class, 'gateway']); // 支付渠道
            Route::post('pay', [Api\PaymentController::class, 'pay']); // 調用渠道支付
        });

        // 購買商品
        Route::post('purchase', [Api\PurchaseController::class, 'purchase']);

        // 用戶反饋
        // Route::prefix('feedback')->group(function () {
        //     Route::get('/', [Api\FeedbackController::class, 'questionnaire']);
        // });
    });
});

// 路由不存在时返回 json error
Route::fallback(function () {
    return response()->json([
        'code' => 200,
        'msg' => 'Route Not Found!',
    ], 404);
});

/*
// 簡訊
Route::prefix('sms')->group(function () {
    Route::post('/verify', [Api\SmsController::class, 'verify'])O
    Route::post('/send', [Api\SmsController::class, 'send']);
});

// 评论
Route::prefix('comment')->group(function () {
    Route::get('list/{chapter_id}/{order}', [Api\CommentController::class, 'list']);
    Route::post('/add', [Api\CommentController::class, 'add']);
    Route::post('/like/{comment_id}', [Api\CommentController::class, 'like']);
    Route::post('/destroy/{comment_id}', [Api\CommentController::class, 'destroy']);
});
*/
