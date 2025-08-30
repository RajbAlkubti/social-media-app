<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\SearchController;

use App\Models\Post;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth')->group(function () {
    Route::middleware('role:user')->group(function () {
        Route::get('/home', function () {
            return view('home');
        });
        
        Route::get('/home', [PostController::class, 'getPosts'])->name('home');
       
        Route::get('/connections', function () {
            return view('connections');
        });

        Route::get('/explore', function () {
            return view('explore');
        });

        Route::get('/settings', function () {
            return view('settings');
        });

        Route::resource('posts', PostController::class);
        Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
        Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
        Route::put('/posts/{id}', [PostController::class, 'update'])->name('post.update');
        Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');
        Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('post.like');
        Route::post('/posts/{post}/repost', [PostController::class, 'toggleRepost'])->name('post.repost');
 
        Route::post('/search', [SearchController::class, 'search'])->name('search');

        Route::post('/comments/{post}/comment', [CommentController::class, 'store'])->name('comment.store');
        Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comment.edit');
        Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comment.update');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');

        Route::resource('profile', UserController::class);


        Route::post('/follow/{user}', [ConnectionController::class, 'follow'])->name('follow');
        Route::delete('/unfollow/{user}', [ConnectionController::class, 'unfollow'])->name('unfollow');
        Route::post('/follow/{id}', [ConnectionController::class, 'follow'])->name('follow');


        Route::get('/settings', [UserController::class, 'edit'])->name('settings.edit');
        Route::post('/settings', [UserController::class, 'update'])->name('settings.update');
        Route::post('/remove-profile-picture', [UserController::class, 'removeProfilePicture'])->name('profile.removePicture');
        Route::put('/settings/security', [UserController::class, 'updateSecurityData'])->name('settings.updateSecurity');
    });

    Route::get('/admin', function () {
        return view('admin');
    })->middleware("role:admin");
    Route::get('/admin', [UserController::class, 'index'])->middleware("role:admin");
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('role:admin');
    Route::delete('/posts/{post}', [PostController::class, 'adminDestroyPost'])->middleware('role:admin')->name('admin.destroy');
});


Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'create'])->name('users.create');
    Route::post('/register', [UserController::class, 'store'])->name('users.store');

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [UserController::class, 'login']);

    Route::get('/', function () {
        return view('login');
    });
});

Route::post('/logout', [UserController::class, 'logout']);
