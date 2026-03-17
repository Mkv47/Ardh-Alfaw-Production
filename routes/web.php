<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TenderController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MessageController;
use Illuminate\Support\Facades\Route;

/* ── Public ── */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/* ── Admin Auth ── */
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout',[AuthController::class, 'logout'])->name('logout');

    /* ── Protected admin routes ── */
    Route::middleware('admin')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('settings',  [SettingsController::class, 'index'])->name('settings');
        Route::put('settings',  [SettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/logo',      [SettingsController::class, 'updateLogo'])->name('settings.logo');
        Route::post('settings/hero-logo', [SettingsController::class, 'updateHeroLogo'])->name('settings.hero-logo');

        Route::resource('services', ServiceController::class)->except(['show']);
        Route::resource('projects', ProjectController::class)->except(['show']);
        Route::resource('news',     NewsController::class)->except(['show']);
        Route::resource('team',     TeamMemberController::class)->except(['show']);
        Route::resource('tenders',  TenderController::class)->except(['show']);
        Route::resource('clients',  ClientController::class)->except(['show']);
        Route::resource('gallery',      GalleryController::class)->except(['show']);
        Route::resource('certificates', CertificateController::class)->except(['show']);

        Route::get('messages',             [MessageController::class, 'index'])->name('messages.index');
        Route::delete('messages/{message}',[MessageController::class, 'destroy'])->name('messages.destroy');
    });
});
