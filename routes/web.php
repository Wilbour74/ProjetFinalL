<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DiscussionController;
use Illuminate\Support\Facades\Route;

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/', function () {
    return view('welcome');
});

Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

Route::get('/comments/{id}', [CommentController::class, 'show'])->name('comments.show');

Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{comment}', [CommentController::class, 'createReply'])->name('comments.reply');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/discussion', [DiscussionController::class, 'create'])->name('discussion.create');
Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
Route::post('/message/{discussion}', [DiscussionController::class, 'storeMessage'])->name('messages.store');
Route::get('/discussion/{discussion}', [DiscussionController::class, 'show'])->name('discussions.show');
// Route publique pour voir le profil d'un utilisateur
Route::get('/users/{username}', [ProfileController::class, 'show'])->name('profile.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
