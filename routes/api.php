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

    // Route::get('/', function () {
    //     return response()->json([
    //         'code' => 200,
    //         'msg'  => 'Hello World!' . config('api.version'),
    //     ]);
    // });

    Route::prefix(config('api.version'))->middleware(['api.header', 'api.sign'])->group(function () {
        Route::prefix('bootstrap')->as('bootstrap.')->group(function () {
            Route::get('/configs', [Api\BootstrapController::class, 'configs'])->name('configs');
        });

        Route::prefix('auth')->as('auth.')->group(function () {
            Route::post('/login', [Api\AuthController::class, 'login'])->name('login');
            Route::post('/register', [Api\AuthController::class, 'register'])->name('register');
        });

        // 首頁:導航列
        Route::prefix('navigation')->as('navigation.')->group(function () {
            Route::get('/list', [Api\NavigationController::class , 'list'])->name('list');
        });

        // 首頁:主题
        Route::prefix('topic')->as('topic.')->group(function () {
            Route::get('/{causer}', [Api\TopicController::class, 'list'])->name('list');
            Route::get('/more/{topic}/{page?}', [Api\TopicController::class, 'more'])->name('more');
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

        // 分類標籤
        Route::prefix('tag')->as('tag.')->group(function () {
            Route::get('/', [Api\TagController::class, 'list'])->name('list');
            Route::get('/book/{tag}/{page?}', [Api\TagController::class, 'book'])->name('book');
            Route::get('/video/{tag}/{page?}', [Api\TagController::class, 'video'])->name('video');
        });

        // 漫畫
        Route::prefix('book')->as('book.')->group(function () {
            Route::get('/{id}', [Api\BookController::class, 'detail'])->name('detail');
            Route::get('/{id}/chapters', [Api\BookController::class, 'chapters'])->name('chapters');
            Route::get('/{id}/chapter/{chapter_id}/{page?}', [Api\BookController::class, 'chapter'])->name('chapter');
            Route::get('/recommend/{id?}', [Api\BookController::class, 'recommend'])->name('recommend');
            Route::post('/report/{type_id}/{id}', [Api\ReportController::class, 'report'])->name('report');
        });

        // 排行榜
        Route::prefix('ranking')->as('ranking.')->group(function () {
            Route::get('/day', [Api\RankingController::class, 'day'])->name('day');
            Route::get('/week', [Api\RankingController::class, 'week'])->name('week');
            Route::get('/month', [Api\RankingController::class, 'month'])->name('month');
            Route::get('/year', [Api\RankingController::class, 'year'])->name('year');
            Route::get('/japan', [Api\RankingController::class, 'japan'])->name('japan');
            Route::get('/korea', [Api\RankingController::class, 'korea'])->name('korea');
            Route::get('/latest', [Api\RankingController::class, 'latest'])->name('latest');
        });

        // 客服
        Route::prefix('service')->as('service.')->group(function () {
            Route::get('/url', [Api\ServiceController::class, 'url'])->name('url');
        });
    });

    Route::prefix(config('api.version'))->middleware(['api.header', 'api.sign', 'auth:sanctum'])->group(function () {
        // 会员
        Route::prefix('auth')->as('auth.')->group(function () {
            Route::get('/profile', [Api\AuthController::class, 'profile'])->name('profile');
            Route::get('/logout', [Api\AuthController::class, 'logout'])->name('logout');
        });

        // 歷史紀錄 (閱覽/ 播放/ 收藏)
        Route::prefix('history')->as('history.')->group(function () {
            // 閱覽 (訪問) 歷史紀錄
            Route::get('/visit/{type}', [Api\VisitHistoryController::class, 'list'])->name('visit.history');
            Route::post('visit/{type}/destroy', [Api\VisitHistoryController::class, 'destroy'])->name('visit.history');

            // 收藏 (最愛) 歷史紀錄
            Route::get('/favorite/{type}', [Api\FavoriteHistoryController::class, 'list'])->name('favorite.history');
            Route::post('/favorite/{type}/save', [Api\FavoriteHistoryController::class, 'save'])->name('favorite.save');
            Route::post('/favorite/{type}/destroy', [Api\FavoriteHistoryController::class, 'destroy'])->name('favorite.history');
        });

        // 支付中心
        Route::prefix('payment')->as('payment.')->group(function () {
            Route::get('/pricing', [Api\PaymentController::class, 'pricing'])->name('pricing'); // 支付方案
            Route::get('/gateway/{pricing_id}', [Api\PaymentController::class, 'gateway'])->name('gateway'); // 支付渠道
            Route::post('/pay', [Api\PaymentController::class, 'pay'])->name('pay'); // 調用渠道支付
        });
    });

    // 第三方支付回調
    Route::prefix('payment')->as('payment.')->group(function () {
        Route::any('/callback', [Api\PaymentController::class, 'callback'])->name('callback'); // 支付結果回調
        Route::any('/mockCallback', [Api\PaymentController::class, 'mockCallback']); // 支付結果回調
    });
});

// 路由不存在时返回 json error
Route::fallback(function () {
    return response()->json([
        'code' => 200,
        'msg'  => 'Route Not Found!',
    ], 404);
});

/*Route::prefix('user')->as('user.')->group(function () {
    Route::get('/login', [Api\UserController::class, 'login'])->name('login');
});

// 会员
Route::prefix('user')->as('user.')->group(function () {
    // Route::get('/device', [Api\UserController::class, 'device'])->name('device');
    // Route::post('/mobile', [UserController::class, 'mobile'])->name('mobile')->middleware('sso');
    // Route::post('/mobile', [Api\UserController::class, 'mobile'])->name('mobile');
    // Route::get('/logout', [Api\UserController::class, 'logout'])->name('logout');
    // Route::get('/logout', [Api\UserController::class, 'logout'])->name('logout');
    // Route::post('/modify', [Api\UserController::class, 'modify'])->name('modify');
    // Route::post('/avatar', [Api\UserController::class, 'avatar'])->name('avatar');
    // Route::post('/sign', [Api\UserController::class, 'sign'])->name('sign');

    // 歷史紀錄 (閱覽/ 播放/ 收藏)
    Route::get('/{type}/visit/history', [Api\VisitHistoryController::class, 'list'])->name('visit.history');
    Route::get('/comment/{page?}', [Api\CommentController::class, 'comment'])->name('comment');
});


// 簡訊
Route::prefix('sms')->as('sms.')->group(function () {
    Route::post('/verify', [Api\SmsController::class, 'verify'])->name('verify'); // 校验SSO
    Route::post('/send', [Api\SmsController::class, 'send'])->name('send');
});

// 广告
Route::prefix('ad')->as('ad.')->group(function () {
    Route::get('/space/{id}', [Api\AdController::class, 'space'])->name('space');
});

// 动画
Route::prefix('video')->as('video.')->group(function () {
    Route::get('/list/{page?}', [Api\VideoController::class, 'list'])->name('list');
    Route::get('/detail/{id}', [Api\VideoController::class, 'detail'])->name('detail');
    Route::get('/recommend/{id?}', [Api\VideoController::class, 'recommend'])->name('recommend');
    Route::post('/play/{id}/{series_id}', [Api\VideoController::class, 'play'])->name('play');
});

Route::prefix('test')->group(function () {
    Route::get('/create/account', [Api\PricingController::class, 'testCreateAccount']);
    Route::post('/balance/transfer', [Api\PricingController::class, 'testBalanceTransfer']);
});

// 评论
Route::prefix('comment')->as('comment.')->group(function () {
    Route::get('/list/{chapter_id}/{order}', [Api\CommentController::class, 'list'])->name('list');
    Route::post('/add', [Api\CommentController::class, 'add'])->name('add');
    Route::post('/like/{comment_id}', [Api\CommentController::class, 'like'])->name('like');
    Route::post('/destroy/{comment_id}', [Api\CommentController::class, 'destroy'])->name('destroy');
});

*/
