<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\ForgetPasswordController;
use \App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\CommentController;

//Email verification group route
Route::group(['middleware' => 'authenticateUser'], function () {

    //display user needs email verification
    Route::get('/email/verify', [AuthController::class, 'verificationNotice'])->name('verification.notice');

    //verify a user email verification link
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');

    //generate a verification link for unverified user
    Route::post('/email/verification-notification', [AuthController::class, 'requestNewVerificationEmail'])->middleware(['throttle:6,1'])->name('verification.send');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

//forgetPassword
Route::Post('/forget-password', [ForgetPasswordController::class, 'sendResetLinkEmail']);
Route::Post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

//users
Route::group(['prefix' => '', 'middleware' => 'authenticateUser'], function () {
    Route::get('/users', [UserController::class, 'index'])->middleware('adminRole');
    Route::post('/users', [UserController::class, 'store'])->withoutMiddleware('authenticateUser');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware(['deleteAuth:{user}']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware(['permission:can-update-user','updateUser']);
    Route::post('/users/role', [UserController::class, 'updateUserRole'])->middleware('adminRole');
});

//posts
Route::group(['prefix' => '', 'middleware' => 'authenticateUser'], function () {
    Route::get('/posts', [PostController::class, 'index'])->withoutMiddleware('authenticateUser')->middleware('permission:can-view-post');
    Route::post('/posts', [PostController::class, 'store'])->middleware('permission:can-add-post');
    Route::get('/posts/{post}', [PostController::class, 'show'])->withoutMiddleware(['permission:can-view-post', 'authenticateUser']);
    Route::put('/posts/{post}', [PostController::class, 'update'])->middleware(['permission:can-update-post', 'updatePost']);
    Route::post('/posts/add-user/{post}', [PostController::class, 'addUserToPost'])->middleware(['permission:can-add-user-to-post']);
    Route::delete('delete-post/{post}', [PostController::class, 'destroy'])->middleware(['permission:can-delete-post']);
    Route::delete('exit-post/{post}', [PostController::class, 'exitPost'])->middleware(['permission:can-delete-post']);
});

//comments
Route::group(['prefix' => '', 'middleware' => 'authenticateUser'], function () {
    Route::get('/comments', [CommentController::class, 'index'])->middleware('adminRole');
    Route::get('/comment/{post}', [CommentController::class, 'show']);
    Route::post('/comment/{post}', [CommentController::class, 'store'])->middleware('permission:can-create-comment');
    Route::put('/comment/{comment}', [CommentController::class, 'update'])->middleware(['permission:can-edit-comment', 'CanAlterComment']);
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->middleware(['permission:can-delete-comment', 'CanAlterComment']);
});
