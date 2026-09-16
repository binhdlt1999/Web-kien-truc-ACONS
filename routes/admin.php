<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectImageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('/pages', [PageContentController::class, 'index'])->name('pages.index');
        Route::get('/pages/home', [PageContentController::class, 'editHome'])->name('pages.home.edit');
        Route::put('/pages/home', [PageContentController::class, 'updateHome'])->name('pages.home.update');
        Route::get('/pages/{page}/edit', [PageContentController::class, 'edit'])
            ->whereIn('page', ['services', 'contact', 'about', 'epsilon', 'resources'])
            ->name('pages.edit');
        Route::put('/pages/{page}', [PageContentController::class, 'update'])
            ->whereIn('page', ['services', 'contact', 'about', 'epsilon', 'resources'])
            ->name('pages.update');

        Route::resource('projects', ProjectController::class)->except('show');
        Route::delete('/project-images/{projectImage}', ProjectImageController::class)
            ->name('project-images.destroy');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('services', ServiceController::class)->except('show');
        Route::resource('articles', ArticleController::class)->except('show');
        Route::resource('testimonials', TestimonialController::class)->except('show');
        Route::resource('partners', PartnerController::class)->except('show');
        Route::resource('contacts', ContactController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
