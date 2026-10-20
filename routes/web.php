<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TopicController;
use App\Models\Topic;
use Dom\Document;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showlogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); 
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/search', [SearchController::class, 'index'])->name('search');
});

Route::get('/docs', [DocumentController::class, 'index'])->name('docs.index');
Route::get('/docs/create', [DocumentController::class, 'create'])->name('docs.create');
Route::post('/docs/', [DocumentController::class, 'store'])->name('docs.store');
Route::get('/docs/{document:slug}/edit', [DocumentController::class, 'edit'])->name('docs.edit');
Route::put('/docs/{document:slug}', [DocumentController::class, 'update'])->name('docs.update');
Route::get('/docs/{document:slug}', [DocumentController::class, 'show'])->name('docs.show');
Route::delete('/docs/{document:slug}', [DocumentController::class, 'destroy'])->name('docs.destroy');

Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');
Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
Route::get('/topics/{topic:slug}/edit', [TopicController::class, 'edit'])->name('topics.edit');
Route::get('/topics/{topic:slug}', [TopicController::class, 'show'])->name('topics.show');
Route::put('/topics/{topic:slug}', [TopicController::class, 'update'])->name('topics.update');
Route::delete('/topics/{topic:slug}', [TopicController::class, 'destroy'])->name('topics.destroy');
    
// require __DIR__.'/auth.php';
