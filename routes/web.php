<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\PlayController;
use App\Models\Quiz;
use Illuminate\Support\Facades\Route;

// プレイヤー画面
Route::get('/', [PlayController::class, 'top'])->name('top');
Route::prefix('categories/{categoryId}')->name('categories.')->group(function () {
    // クイズスタート画面
    Route::get('/', [PlayController::class, 'categories'])->name('start');
    // クイズ出題画面
    Route::get('quizzes', [PlayController::class, 'quizzes'])->name('quizzes');
    // クイズ解答画面
    Route::post('quizzes/answer', [PlayController::class, 'answer'])->name('quizzes.answer');
    // リザルト画面
    Route::get('quizzes/result', [PlayController::class, 'result'])->name('quizzes.result');
});

// 管理者の認証機能
require __DIR__.'/auth.php';

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // 管理画面トップページ
    // カテゴリー一覧表示
    Route::get('top', [CategoryController::class, 'top'])->name('top');

    // カテゴリー管理
    Route::prefix('categories')->name('categories.')->group(function () {
        // カテゴリー新規登録画面
        Route::get('create', [CategoryController::class, 'create'])->name('create');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::get('{categoryId}', [CategoryController::class, 'show'])->name('show');
        Route::get('{categoryId}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::post('{categoryId}/update', [CategoryController::class, 'update'])->name('update');
        Route::post('{categoryId}/destroy', [CategoryController::class, 'destroy'])->name('destroy');

        Route::prefix('{categoryId}/quizzes')->name('quizzes.')->group(function () {
            // クイズ新規登録画面
            Route::get('create', [QuizController::class, 'create'])->name('create');
            Route::post('store', [QuizController::class, 'store'])->name('store');
            Route::get('{quizId}/edit', [QuizController::class, 'edit'])->name('edit');
            Route::post('{quizId}/update', [QuizController::class, 'update'])->name('update');
            Route::post('{quizId}/destroy', [QuizController::class, 'destroy'])->name('destroy');
        });
    });

});
