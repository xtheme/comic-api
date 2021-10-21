<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend;
use App\Http\Controllers\Frontend;
use App\Http\Controllers\LanguageController;
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
// Auth::routes(['verify' => true]);
Route::get('login/{secret?}', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Homepage
Route::get('/', [Frontend\HomeController::class, 'index']);
Route::get('403', [Frontend\HomeController::class, 'noPermission'])->name('403');
Route::get('404', [Frontend\HomeController::class, 'notFound'])->name('404');
Route::get('500', [Frontend\HomeController::class, 'internalError'])->name('500');

// Backend iframe layout
Route::middleware(['auth'])->group(function () {
    Route::get('backend', [Backend\DashboardController::class, 'index']);
    Route::post('upload/{dir?}/{id?}', [UploadController::class, 'upload'])->name('upload'); // 單檔案上傳
    Route::post('unlink', [UploadController::class, 'unlink'])->name('unlink'); // 單檔案刪除
    // Route::post('editor/upload/{dir?}/{id?}', [UploadController::class, 'editorUpload'])->name('editor.upload'); // CKEditor
});

// Backend iframe pages
Route::middleware(['auth', 'auth.route.role', 'log.activity'])->prefix('backend')->as('backend.')->group(function () {
    Route::get('dashboard', [Backend\DashboardController::class, 'dashboard'])->name('dashboard');

    // 系统配置
    Route::prefix('config')->as('config.')->group(function () {
        Route::get('/', [Backend\ConfigController::class, 'index'])->name('index');
        Route::get('create', [Backend\ConfigController::class , 'create'])->name('create');
        Route::post('store', [Backend\ConfigController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\ConfigController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\ConfigController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\ConfigController::class , 'destroy'])->name('destroy');
    });

    // 用户管理
    Route::prefix('user')->as('user.')->group(function () {
        Route::get('/', [Backend\UserController::class, 'index'])->name('index');
        Route::get('edit/{id}', [Backend\UserController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\UserController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\UserController::class , 'destroy'])->name('destroy'); // 軟刪除
        Route::put('batch/{action?}', [Backend\UserController::class, 'batch'])->name('batch'); // 批量操作
        Route::put('editable/{field}', [Backend\UserController::class, 'editable'])->name('editable');

        Route::get('gift/{id}', [Backend\UserController::class , 'gift'])->name('gift');
        Route::put('updateGift/{id}', [Backend\UserController::class , 'updateGift'])->name('update.gift');

        Route::get('order/{id}', [Backend\UserController::class , 'order'])->name('order');
        Route::get('recharge/{id}', [Backend\UserController::class , 'recharge'])->name('recharge');
        Route::get('purchase/{id}', [Backend\UserController::class , 'purchase'])->name('purchase');
    });

    // 订单
    Route::prefix('order')->as('order.')->group(function () {
        Route::get('/', [Backend\OrderController::class, 'index'])->name('index');        // 订单列表
        Route::get('export', [Backend\OrderController::class, 'export'])->name('export'); // 汇出订单
        Route::put('callback/{id}', [Backend\OrderController::class, 'callback'])->name('callback'); // 第三方更改訂單狀態失效時手動回調
    });

    // 分类
    Route::prefix('category')->as('category.')->group(function () {
        Route::get('/', [Backend\CategoryController::class , 'index'])->name('index');
        Route::get('create', [Backend\CategoryController::class , 'create'])->name('create');
        Route::post('store', [Backend\CategoryController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\CategoryController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\CategoryController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\CategoryController::class , 'destroy'])->name('destroy');
    });

    // 標籤
    Route::prefix('tag')->as('tag.')->group(function () {
        Route::get('/', [Backend\TagController::class, 'index'])->name('index');
        Route::get('create', [Backend\TagController::class , 'create'])->name('create');
        Route::post('store', [Backend\TagController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\TagController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\TagController::class , 'update'])->name('update');
        Route::put('batch/{action?}', [Backend\TagController::class, 'batch'])->name('batch');
        Route::put('editable/{field}', [Backend\TagController::class, 'editable'])->name('editable');
        Route::delete('destroy/{name}', [Backend\TagController::class , 'destroy'])->name('destroy');
    });

    // 漫画
    Route::prefix('book')->as('book.')->group(function () {
        Route::get('/', [Backend\BookController::class, 'index'])->name('index');
        Route::get('create', [Backend\BookController::class , 'create'])->name('create');
        Route::post('store', [Backend\BookController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\BookController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\BookController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\BookController::class , 'destroy'])->name('destroy'); // 軟刪除
        Route::put('batch/{action?}', [Backend\BookController::class, 'batch'])->name('batch');
        Route::put('editable/{field}', [Backend\BookController::class, 'editable'])->name('editable');
        Route::get('caching', [Backend\BookController::class, 'caching'])->name('caching'); // 下載CDN緩存文件
        Route::get('price', [Backend\BookController::class, 'price'])->name('price'); // 下載CDN緩存文件
        Route::put('revise/price', [Backend\BookController::class, 'revisePrice'])->name('revise.price'); // 下載CDN緩存文件
    });

    // 漫画章节
    Route::prefix('book_chapter')->as('book_chapter.')->group(function () {
        Route::get('/{book_id}', [Backend\BookChapterController::class, 'index'])->name('index'); // 章節列表
        Route::get('preview/{id}', [Backend\BookChapterController::class, 'preview'])->name('preview'); // 章節預覽
        Route::get('create/{book_id}', [Backend\BookChapterController::class , 'create'])->name('create'); // 添加章節
        Route::post('store/{book_id}', [Backend\BookChapterController::class , 'store'])->name('store');
        Route::get('edit/{chapter_id}', [Backend\BookChapterController::class , 'edit'])->name('edit');
        Route::put('update/{chapter_id}', [Backend\BookChapterController::class , 'update'])->name('update');
        Route::put('batch/{action?}', [Backend\BookChapterController::class, 'batch'])->name('batch'); // 批量操作
        Route::put('editable/{field}', [Backend\BookChapterController::class, 'editable'])->name('editable');
    });

    // 支付方案
    Route::prefix('pricing')->as('pricing.')->group(function () {
        Route::get('/', [Backend\PricingController::class , 'index'])->name('index');
        Route::get('create', [Backend\PricingController::class , 'create'])->name('create');
        Route::post('store', [Backend\PricingController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\PricingController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\PricingController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\PricingController::class , 'destroy'])->name('destroy');
    });

    // 支付渠道
    Route::prefix('payment')->as('payment.')->group(function () {
        Route::get('/', [Backend\PaymentController::class , 'index'])->name('index');
        Route::get('create', [Backend\PaymentController::class , 'create'])->name('create');
        Route::post('store', [Backend\PaymentController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\PaymentController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\PaymentController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\PaymentController::class , 'destroy'])->name('destroy');
    });

    // 評論
    Route::prefix('comment')->as('comment.')->group(function () {
        Route::get('/', [Backend\CommentController::class , 'index'])->name('index');
        Route::delete('destroy/{id}', [Backend\CommentController::class , 'destroy'])->name('destroy');
        Route::post('batch/destroy', [Backend\CommentController::class, 'batchDestroy'])->name('batch_destroy');
    });

    // 公告
    Route::prefix('notice')->as('notice.')->group(function () {
        Route::get('/', [Backend\NoticeController::class , 'index'])->name('index');
        Route::get('create', [Backend\NoticeController::class , 'create'])->name('create');
        Route::post('store', [Backend\NoticeController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\NoticeController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\NoticeController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\NoticeController::class , 'destroy'])->name('destroy');
    });

    // 導航列
    Route::prefix('navigation')->as('navigation.')->group(function () {
        Route::get('/', [Backend\NavigationController::class , 'index'])->name('index');
        Route::get('create', [Backend\NavigationController::class , 'create'])->name('create');
        Route::post('store', [Backend\NavigationController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\NavigationController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\NavigationController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\NavigationController::class , 'destroy'])->name('destroy');
    });

    // 举报类型
    Route::prefix('report_type')->as('report_type.')->group(function () {
        Route::get('/', [Backend\ReportTypeController::class , 'index'])->name('index');
        Route::get('create', [Backend\ReportTypeController::class , 'create'])->name('create');
        Route::post('store', [Backend\ReportTypeController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\ReportTypeController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\ReportTypeController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\ReportTypeController::class , 'destroy'])->name('destroy');
        Route::put('sort', [Backend\ReportTypeController::class , 'sort'])->name('sort');
    });

    // 用户举报
    Route::prefix('report')->as('report.')->group(function () {
        Route::get('/', [Backend\ReportController::class , 'index'])->name('index');
    });

    // 视频
    Route::prefix('video')->as('video.')->group(function () {
        Route::get('/', [Backend\VideoController::class , 'index'])->name('index');
        Route::get('create', [Backend\VideoController::class , 'create'])->name('create');
        Route::post('store', [Backend\VideoController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\VideoController::class , 'edit'])->name('edit');
        Route::post('update/{id}', [Backend\VideoController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\VideoController::class , 'destroy'])->name('destroy');
        Route::put('batch/{action?}', [Backend\VideoController::class, 'batch'])->name('batch');
        Route::put('editable/{field}', [Backend\VideoController::class, 'editable'])->name('editable');
    });

    // Route::prefix('video_series')->as('video_series.')->group(function () {
    //     Route::get('/{video_id}', [Backend\VideoSeriesController::class , 'index'])->name('index');
    //     Route::get('create/{video_id}', [Backend\VideoSeriesController::class , 'create'])->name('create');
    //     Route::post('store/{video_id}', [Backend\VideoSeriesController::class , 'store'])->name('store');
    //     Route::get('edit/{video_id}/{id}', [Backend\VideoSeriesController::class , 'edit'])->name('edit');
    //     Route::post('update/{video_id}/{id}', [Backend\VideoSeriesController::class , 'update'])->name('update');
    //     Route::delete('destroy/{id}', [Backend\VideoSeriesController::class , 'destroy'])->name('destroy');
    //     Route::put('batch/{action?}', [Backend\VideoSeriesController::class, 'batch'])->name('batch');
    //     Route::put('editable/{field}', [Backend\VideoSeriesController::class, 'editable'])->name('editable');
    //     Route::any('preview/{id}', [Backend\VideoSeriesController::class, 'preview'])->name('preview');
    // });

    Route::prefix('video_domain')->as('video_domain.')->group(function () {
        Route::get('/', [Backend\VideoDomainController::class , 'index'])->name('index');
        Route::get('create', [Backend\VideoDomainController::class , 'create'])->name('create');
        Route::post('store', [Backend\VideoDomainController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\VideoDomainController::class , 'edit'])->name('edit');
        Route::post('update/{id}', [Backend\VideoDomainController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\VideoDomainController::class , 'destroy'])->name('destroy');
        Route::get('series/{id}', [Backend\VideoDomainController::class , 'series'])->name('series');
        Route::put('change_domain', [Backend\VideoDomainController::class , 'change_domain'])->name('change_domain');
        // Route::put('batch/{action?}', [Backend\VideoDomainController::class, 'batch'])->name('batch');
        Route::put('editable/{field}', [Backend\VideoDomainController::class, 'editable'])->name('editable');
    });

    // 广告位
    Route::prefix('ad_space')->as('ad_space.')->group(function () {
        Route::get('/', [Backend\AdSpaceController::class , 'index'])->name('index');
        Route::get('edit/{id}', [Backend\AdSpaceController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\AdSpaceController::class , 'update'])->name('update');
    });

    // 广告
    Route::prefix('ad')->as('ad.')->group(function () {
        Route::get('/', [Backend\AdController::class , 'index'])->name('index');
        Route::get('create', [Backend\AdController::class , 'create'])->name('create');
        Route::post('store', [Backend\AdController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\AdController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\AdController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\AdController::class , 'destroy'])->name('destroy');
        Route::put('sort', [Backend\AdController::class , 'sort'])->name('sort');
        Route::put('batch/{action?}', [Backend\AdController::class, 'batch'])->name('batch'); // 批量操作
    });

    // 主题模块
    Route::prefix('topic')->as('topic.')->group(function () {
        Route::get('/', [Backend\TopicController::class , 'index'])->name('index');
        Route::get('create', [Backend\TopicController::class , 'create'])->name('create');
        Route::post('store', [Backend\TopicController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\TopicController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\TopicController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\TopicController::class , 'destroy'])->name('destroy');
        Route::put('sort', [Backend\TopicController::class , 'sort'])->name('sort');
        Route::put('batch/{action?}', [Backend\TopicController::class, 'batch'])->name('batch');
    });

    // 数据统计
    Route::prefix('statistics')->as('statistics.')->group(function () {
        Route::get('/', [Backend\StatisticsController::class , 'index'])->name('index');
        Route::get('/series/{video_id}', [Backend\StatisticsController::class , 'series'])->name('series');
    });

    // 操作记录
    Route::prefix('activity')->as('activity.')->group(function () {
        Route::get('/', [Backend\ActivityLogController::class , 'index'])->name('index');
        Route::get('/diff/{id}', [Backend\ActivityLogController::class , 'diff'])->name('diff'); // 查看差異
        Route::post('/restore/{id}', [Backend\ActivityLogController::class , 'restore'])->name('restore'); // 數據回滾
    });

    // 管理員
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::get('/', [Backend\AdminController::class, 'index'])->name('index');
        Route::get('create', [Backend\AdminController::class, 'create'])->name('create');
        Route::post('store', [Backend\AdminController::class, 'store'])->name('store');
        Route::get('edit/{id}', [Backend\AdminController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\AdminController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\AdminController::class, 'destroy'])->name('destroy');
        Route::put('batch/{action?}', [Backend\AdminController::class, 'batch'])->name('batch');
    });

    // 角色
    Route::prefix('role')->as('role.')->group(function () {
        Route::get('/', [Backend\RoleController::class , 'index'])->name('index');
        Route::get('create', [Backend\RoleController::class , 'create'])->name('create');
        Route::post('store', [Backend\RoleController::class , 'store'])->name('store');
        Route::get('edit/{id}', [Backend\RoleController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [Backend\RoleController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [Backend\RoleController::class , 'destroy'])->name('destroy');
    });

});
