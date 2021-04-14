<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\BookCategoryController;
use App\Http\Controllers\Backend\BookChapterController;
use App\Http\Controllers\Backend\BookController;
use App\Http\Controllers\Backend\CommentController;
use App\Http\Controllers\Backend\ConfigController;
use App\Http\Controllers\Backend\FeedbackController;
use App\Http\Controllers\Backend\NoticeController;
use App\Http\Controllers\Backend\PricingpackageController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PricingController;
use App\Http\Controllers\Backend\UserController;
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
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Homepage
Route::get('/', [HomeController::class, 'index']);

// Route::prefix('requisition')->as('requisition.')->group(function () {
//     Route::get('create', [RequisitionController::class, 'create'])->name('create');
//     Route::post('store', [RequisitionController::class, 'store'])->name('store');
// });

// Backend iframe layout
Route::middleware(['auth'])->group(function () {
    Route::get('backend', [DashboardController::class, 'index']);
    Route::post('upload/{dir?}/{id?}', [UploadController::class, 'upload'])->name('upload'); // 單檔案上傳
    Route::post('editor/upload/{dir?}/{id?}', [UploadController::class, 'editorUpload'])->name('editor.upload'); // CKEditor
});

// Backend iframe pages
Route::middleware(['auth'])->prefix('backend')->as('backend.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // 系统配置
    Route::resource('config', ConfigController::class);

    // 用户管理
    Route::put('user/{id}/block', [UserController::class, 'block'])->name('user.block'); // 切换用户状态
    Route::resource('user', UserController::class);

    // 订单
    Route::prefix('order')->as('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');        // 订单列表
        Route::get('export', [OrderController::class, 'export'])->name('export'); // 汇出订单
    });

    // 漫画
    Route::prefix('book')->as('book.')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('index');
        // Route::put('editable/{field}', [BookCategoryController::class, 'editable'])->name('editable');
    });
    // Route::resource('book', BookController::class);

    // 漫画分类
    Route::prefix('book_category')->as('book_category.')->group(function () {
        Route::get('/', [BookCategoryController::class, 'index'])->name('index');
        Route::put('editable/{field}', [BookCategoryController::class, 'editable'])->name('editable');
    });
    // Route::resource('book_category', BookCategoryController::class);

    // 漫画章节
    Route::prefix('book_chapter')->as('book_chapter.')->group(function () {
        Route::get('/', [BookChapterController::class, 'index'])->name('index');
    });
    // Route::resource('book_chapter', BookChapterController::class);

    Route::resource('pricing', PricingController::class);

    // 物流
    // Route::resource('location', LocationController::class);
    // Route::resource('shipment', ShipmentController::class);
    // Route::resource('requisition', RequisitionController::class);

    // 意見反饋
    Route::prefix('feedback')->as('feedback.')->group(function () {
        Route::get('/', [FeedbackController::class , 'index'])->name('index');
        Route::delete('destroy/{id}', [FeedbackController::class , 'destroy'])->name('destroy');
        Route::post('batch/destroy', [FeedbackController::class, 'batchDestroy'])->name('batch.destroy');
    });

    // 會員套餐
    Route::prefix('pricingpackage')->as('pricingpackage.')->group(function () {
        Route::get('/', [PricingpackageController::class , 'index'])->name('index');
        Route::get('create', [PricingpackageController::class , 'create'])->name('create');
        Route::post('store', [PricingpackageController::class , 'store'])->name('store');
        Route::get('edit/{id}', [PricingpackageController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [PricingpackageController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [PricingpackageController::class , 'destroy'])->name('destroy');
    });

    Route::prefix('comment')->as('comment.')->group(function () {
        Route::get('/', [CommentController::class , 'index'])->name('index');
        Route::delete('destroy/{id}', [CommentController::class , 'destroy'])->name('destroy');
        Route::post('batch/destroy', [CommentController::class, 'batchDestroy'])->name('batch.destroy');
    });

    //公告
    Route::prefix('notice')->as('notice.')->group(function () {
        Route::get('/', [NoticeController::class , 'index'])->name('index');
        Route::get('create', [NoticeController::class , 'create'])->name('create');
        Route::post('store', [NoticeController::class , 'store'])->name('store');
        Route::get('edit/{id}', [NoticeController::class , 'edit'])->name('edit');
        Route::put('update/{id}', [NoticeController::class , 'update'])->name('update');
        Route::delete('destroy/{id}', [NoticeController::class , 'destroy'])->name('destroy');
    });
});
