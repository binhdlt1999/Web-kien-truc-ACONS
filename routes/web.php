<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EpsilonController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/du-an', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/du-an/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/dich-vu', [ServiceController::class, 'index'])->name('services.index');
Route::get('/epsilon', EpsilonController::class)->name('epsilon.index');
Route::get('/tai-nguyen', ResourceController::class)->name('resources.index');
Route::get('/gioi-thieu', AboutController::class)->name('about.index');
Route::get('/tin-tuc/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/lien-he', [ContactController::class, 'create'])->name('contacts.create');
Route::post('/lien-he', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contacts.store');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

require __DIR__.'/admin.php';
