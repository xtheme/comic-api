<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\ActivityLogController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BlockController;
use App\Http\Controllers\Backend\BookChapterController;
use App\Http\Controllers\Backend\BookController;
use App\Http\Controllers\Backend\ComicBlockController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\ReportTypeController;
use App\Http\Controllers\Backend\CommentController;
use App\Http\Controllers\Backend\ConfigController;
use App\Http\Controllers\Backend\FeedbackController;
use App\Http\Controllers\Backend\NoticeController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PricingController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StatisticsController;
use App\Http\Controllers\Backend\TagController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\VideoController;
use App\Http\Controllers\Backend\VideoDomainController;
use App\Http\Controllers\Backend\VideoSeriesController;
use App\Http\Controllers\Backend\AdController;
use App\Http\Controllers\Backend\AdSpaceController;
use App\Http\Controllers\Frontend\HomeController;
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
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout']);

// Homepage
Route::get('/', [HomeController::class, 'index']);
Route::get('403', [HomeController::class, 'noPermission'])->name('403');
Route::get('404', [HomeController::class, 'notFound'])->name('404');
Route::get('500', [HomeController::class, 'internalError'])->name('500');


// Backend iframe layout
Route::middleware(['auth'])->group(function () {
    Route::get('backend', [DashboardController::class, 'index']);
    Route::post('upload/{dir?}/{id?}', [UploadController::class, 'upload'])->name('upload'); // 單檔案上傳
    // Route::post('editor/upload/{dir?}/{id?}', [UploadController::class, 'editorUpload'])->name('editor.upload'); // CKEditor
});

// Backend iframe pages
Route::middleware(['auth', 'auth.route.role', 'log.activity'])->prefix('backend')->as('backend.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // 系统配置
    Route::prefix('config')->as('config.')->group(function () {
        Route::get('/', [ConfigController::class, 'index'])->name('index');
        Route::get('create', [ConfigController::class , 'create'])->name('create');
        Route::post('store', [ConfigController::class , 'store'])->name('store');
        Route::get('edit/{id}', [ConfigController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [ConfigController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [ConfigController::class , 'destroy'])->name('destroy');
    });

    // 用户管理
    Route::prefix('user')->as('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        // Route::get('create', [UserController::class , 'create'])->name('create');
        // Route::post('store', [UserController::class , 'store'])->name('store');
        Route::get('edit/{id}', [UserController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [UserController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [UserController::class , 'destroy'])->name('destroy'); // 軟刪除
        Route::put('block/{id}', [UserController::class, 'block'])->name('block');
        Route::put('batch/{action?}', [UserController::class, 'batch'])->name('batch'); // 批量操作
        Route::put('editable/{field}', [UserController::class, 'editable'])->name('editable');
        // 特殊
        Route::put('unbind/{id}', [UserController::class , 'unbindSso'])->name('unbind');
    });

    Route::prefix('vip')->as('vip.')->group(function () {
        Route::get('edit/vip/{id}', [UserController::class , 'editVip'])->name('edit');
        Route::put('update/vip/{id}', [UserController::class , 'updateVip'])->name('update');
        Route::get('transfer/vip/{id}', [UserController::class , 'transferVip'])->name('transfer');
        Route::put('transfer/vip/{id}', [UserController::class , 'transferUpdate'])->name('transfer_update');
    });

    // 订单
    Route::prefix('order')->as('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');        // 订单列表
        Route::get('export', [OrderController::class, 'export'])->name('export'); // 汇出订单
        Route::put('callback/{id}', [OrderController::class, 'callback'])->name('callback'); // 第三方更改訂單狀態失效時手動回調
    });

    // 漫画分类
    Route::prefix('tag')->as('tag.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::get('create', [TagController::class , 'create'])->name('create');
        Route::post('store', [TagController::class , 'store'])->name('store');
        Route::put('batch/{action?}', [TagController::class, 'batch'])->name('batch');
        Route::put('editable/{field}', [TagController::class, 'editable'])->name('editable');
        Route::delete('destroy/{id}', [TagController::class , 'destroy'])->name('destroy');
    });

    // 漫画
    Route::prefix('book')->as('book.')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('index');
        Route::get('create', [BookController::class , 'create'])->name('create');
        Route::post('store', [BookController::class , 'store'])->name('store');
        Route::get('edit/{id}', [BookController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [BookController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [BookController::class , 'destroy'])->name('destroy'); // 軟刪除
        Route::get('review/{id}', [BookController::class , 'review'])->name('review');
        Route::put('update/review/{id}', [BookController::class , 'updateReview'])->name('review_update');
        Route::put('batch/{action?}', [BookController::class, 'batch'])->name('batch');
        Route::put('editable/{field}', [BookController::class, 'editable'])->name('editable');
        Route::get('caching', [BookController::class, 'caching'])->name('caching'); // 下載CDN緩存文件
    });

    // 漫画章节
    Route::prefix('book_chapter')->as('book_chapter.')->group(function () {
        Route::get('/{book_id}', [BookChapterController::class, 'index'])->name('index'); // 章節列表
        Route::get('preview/{id}', [BookChapterController::class, 'preview'])->name('preview'); // 章節預覽
        Route::get('create/{book_id}', [BookChapterController::class , 'create'])->name('create'); // 添加章節
        Route::post('store/{book_id}', [BookChapterController::class , 'store'])->name('store');
        Route::get('edit/{book_id}/{chapter_id}', [BookChapterController::class , 'edit'])->name('edit');
        Route::put('update/{book_id}/{chapter_id}', [BookChapterController::class , 'update'])->name('update');
        Route::put('batch/{action?}', [BookChapterController::class, 'batch'])->name('batch'); // 批量操作
        Route::put('editable/{field}', [BookChapterController::class, 'editable'])->name('editable');
    });

    // 意見反饋
    Route::prefix('feedback')->as('feedback.')->group(function () {
        Route::get('/', [FeedbackController::class , 'index'])->name('index');
        Route::delete('destroy/{id}', [FeedbackController::class , 'destroy'])->name('destroy');
        Route::post('batch/destroy', [FeedbackController::class, 'batchDestroy'])->name('batch_destroy');
    });

    // 會員套餐
    Route::prefix('pricing')->as('pricing.')->group(function () {
        Route::get('/', [PricingController::class , 'index'])->name('index');
        Route::get('create', [PricingController::class , 'create'])->name('create');
        Route::post('store', [PricingController::class , 'store'])->name('store');
        Route::get('edit/{id}', [PricingController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [PricingController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [PricingController::class , 'destroy'])->name('destroy');
    });

    Route::prefix('comment')->as('comment.')->group(function () {
        Route::get('/', [CommentController::class , 'index'])->name('index');
        Route::delete('destroy/{id}', [CommentController::class , 'destroy'])->name('destroy');
        Route::post('batch/destroy', [CommentController::class, 'batchDestroy'])->name('batch_destroy');
    });

    // 公告
    Route::prefix('notice')->as('notice.')->group(function () {
        Route::get('/', [NoticeController::class , 'index'])->name('index');
        Route::get('create', [NoticeController::class , 'create'])->name('create');
        Route::post('store', [NoticeController::class , 'store'])->name('store');
        Route::get('edit/{id}', [NoticeController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [NoticeController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [NoticeController::class , 'destroy'])->name('destroy');
    });

    // 漫画首頁模塊
    /*Route::prefix('comic_block')->as('comic_block.')->group(function () {
        Route::get('/', [ComicBlockController::class , 'index'])->name('index');
        Route::get('create', [ComicBlockController::class , 'create'])->name('create');
        Route::post('store', [ComicBlockController::class , 'store'])->name('store');
        Route::get('edit/{id}', [ComicBlockController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [ComicBlockController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [ComicBlockController::class , 'destroy'])->name('destroy');
        Route::put('sort', [ComicBlockController::class , 'sort'])->name('sort');
        Route::post('batch/destroy/{ids?}', [ComicBlockController::class, 'batchDestroy'])->name('batch_destroy');
    });*/

    // 举报类型
    Route::prefix('report_type')->as('report_type.')->group(function () {
        Route::get('/', [ReportTypeController::class , 'index'])->name('index');
        Route::get('create', [ReportTypeController::class , 'create'])->name('create');
        Route::post('store', [ReportTypeController::class , 'store'])->name('store');
        Route::get('edit/{id}', [ReportTypeController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [ReportTypeController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [ReportTypeController::class , 'destroy'])->name('destroy');
        Route::put('sort', [ReportTypeController::class , 'sort'])->name('sort');
    });

    // 用户举报
    Route::prefix('report')->as('report.')->group(function () {
        Route::get('/', [ReportController::class , 'index'])->name('index');
    });

    // 视频
    Route::prefix('video')->as('video.')->group(function () {
        Route::get('/', [VideoController::class , 'index'])->name('index');
        Route::get('create', [VideoController::class , 'create'])->name('create');
        Route::post('store', [VideoController::class , 'store'])->name('store');
        Route::get('edit/{id}', [VideoController::class , 'edit'])->name('edit');
        Route::post('update/{id}', [VideoController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [VideoController::class , 'destroy'])->name('destroy');
        Route::put('batch/{action?}', [VideoController::class, 'batch'])->name('batch');
        Route::put('editable/{field}', [VideoController::class, 'editable'])->name('editable');
    });

    Route::prefix('video_series')->as('video_series.')->group(function () {
        Route::get('/{video_id}', [VideoSeriesController::class , 'index'])->name('index');
        Route::get('create/{video_id}', [VideoSeriesController::class , 'create'])->name('create');
        Route::post('store/{video_id}', [VideoSeriesController::class , 'store'])->name('store');
        Route::get('edit/{video_id}/{id}', [VideoSeriesController::class , 'edit'])->name('edit');
        Route::post('update/{video_id}/{id}', [VideoSeriesController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [VideoSeriesController::class , 'destroy'])->name('destroy');
        Route::put('batch/{action?}', [VideoSeriesController::class, 'batch'])->name('batch');
        Route::put('editable/{field}', [VideoSeriesController::class, 'editable'])->name('editable');
        Route::any('preview/{id}', [VideoSeriesController::class, 'preview'])->name('preview');
    });

    Route::prefix('video_domain')->as('video_domain.')->group(function () {
        Route::get('/', [VideoDomainController::class , 'index'])->name('index');
        Route::get('create', [VideoDomainController::class , 'create'])->name('create');
        Route::post('store', [VideoDomainController::class , 'store'])->name('store');
        Route::get('edit/{id}', [VideoDomainController::class , 'edit'])->name('edit');
        Route::post('update/{id}', [VideoDomainController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [VideoDomainController::class , 'destroy'])->name('destroy');
        Route::get('series/{id}', [VideoDomainController::class , 'series'])->name('series');
        Route::put('change_domain', [VideoDomainController::class , 'change_domain'])->name('change_domain');
        // Route::put('batch/{action?}', [VideoDomainController::class, 'batch'])->name('batch');
        Route::put('editable/{field}', [VideoDomainController::class, 'editable'])->name('editable');
    });

    // 广告位
    Route::prefix('ad_space')->as('ad_space.')->group(function () {
        Route::get('/', [AdSpaceController::class , 'index'])->name('index');
        Route::get('edit/{id}', [AdSpaceController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [AdSpaceController::class , 'update'])->name('update');
    });

    // 广告
    Route::prefix('ad')->as('ad.')->group(function () {
        Route::get('/', [AdController::class , 'index'])->name('index');
        Route::get('create', [AdController::class , 'create'])->name('create');
        Route::post('store', [AdController::class , 'store'])->name('store');
        Route::get('edit/{id}', [AdController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [AdController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [AdController::class , 'destroy'])->name('destroy');
        Route::put('sort', [AdController::class , 'sort'])->name('sort');
        Route::put('batch/{action?}', [AdController::class, 'batch'])->name('batch'); // 批量操作
    });

    // 首頁模塊
    Route::prefix('block')->as('block.')->group(function () {
        Route::get('/', [BlockController::class , 'index'])->name('index');
        Route::get('create', [BlockController::class , 'create'])->name('create');
        Route::post('store', [BlockController::class , 'store'])->name('store');
        Route::get('edit/{id}', [BlockController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [BlockController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [BlockController::class , 'destroy'])->name('destroy');
        Route::put('sort', [BlockController::class , 'sort'])->name('sort');
        Route::put('batch/{action?}', [BlockController::class, 'batch'])->name('batch');
    });

    // 数据统计
    Route::prefix('statistics')->as('statistics.')->group(function () {
        Route::get('/', [StatisticsController::class , 'index'])->name('index');
        Route::get('/series/{video_id}', [StatisticsController::class , 'series'])->name('series');
    });

    // 操作记录
    Route::prefix('activity')->as('activity.')->group(function () {
        Route::get('/', [ActivityLogController::class , 'index'])->name('index');
        Route::get('/diff/{id}', [ActivityLogController::class , 'diff'])->name('diff'); // 查看差異
        Route::post('/restore/{id}', [ActivityLogController::class , 'restore'])->name('restore'); // 數據回滾
    });

    // 管理員
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('create', [AdminController::class, 'create'])->name('create');
        Route::post('store', [AdminController::class, 'store'])->name('store');
        Route::get('edit/{id}', [AdminController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [AdminController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [AdminController::class, 'destroy'])->name('destroy');
        Route::put('batch/{action?}', [AdminController::class, 'batch'])->name('batch');
    });

    // 角色
    Route::prefix('role')->as('role.')->group(function () {
        Route::get('/', [RoleController::class , 'index'])->name('index');
        Route::get('create', [RoleController::class , 'create'])->name('create');
        Route::post('store', [RoleController::class , 'store'])->name('store');
        Route::get('edit/{id}', [RoleController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [RoleController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [RoleController::class , 'destroy'])->name('destroy');
    });

});
