<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\SnippetController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\EnsureAdminAuthenticated;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware([EnsureAdminAuthenticated::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('notes', NoteController::class);
    Route::resource('links', LinkController::class);
    Route::resource('snippets', SnippetController::class);
    Route::resource('features', FeatureController::class);
    Route::resource('resources', ResourceController::class);
});
