<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::post('register', [RegisterController::class, 'createUser'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::post('forgot-password', [ResetPasswordController::class, 'resetPassword'])->name('password.email');
Route::post('reset-password/{token}', [ResetPasswordController::class, 'updatePassword'])->name('password.update');

Route::post('successfully-verified/{token}', [RegisterController::class, 'verifyEmail'])->name('user.verify');

Route::post('movies', [MovieController::class, 'store'])->name('movies.store')->middleware('auth:api');

Route::get('genres', [GenreController::class, 'showGenres'])->name('genres.get')->middleware('auth:api');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:api');
Route::get('user-movies', [MovieController::class, 'showUserMovies'])->name('user.movies')->middleware('auth:api');
Route::post('movie/{movie}', [MovieController::class, 'showMovieDescription'])->name('movie.description')->middleware('auth:api');
Route::post('movie/{movie}/quote', [QuoteController::class, 'store'])->name('quote.store')->middleware('auth:api');
Route::get('movie/{movie}', [MovieController::class, 'showMovie'])->name('show.movie')->middleware('auth:api');
Route::get('quote/{quote}', [QuoteController::class, 'showQuote'])->name('quote.create')->middleware('auth:api');
Route::post('quote/{quote}/update', [QuoteController::class, 'update'])->name('quote.update')->middleware('auth:api');
Route::get('movie/{movie}/genres', [MovieController::class, 'showMovieWithGenres'])->name('movie.genres_show')->middleware('auth:api');
Route::post('movie/{movie}/update', [MovieController::class, 'update'])->name('movie.update')->middleware('auth:api');
Route::delete('movie/{movie}/delete', [MovieController::class, 'destroy'])->name('movie.destroy')->middleware('auth:api');
Route::delete('quote/{quote}/delete', [QuoteController::class, 'destroy'])->name('quote.destroy')->middleware('auth:api');
Route::get('feed', [QuoteController::class, 'showAllQuotes'])->name('all.quotes_show')->middleware('auth:api');
Route::get('quote/{quote}/add-like', [LikeController::class, 'store'])->name('like.store')->middleware('auth:api');
Route::get('notifications', [NotificationController::class, 'show'])->name('notifications.show')->middleware('auth:api');
Route::get('notifications/mark-all-as-read', [NotificationController::class, 'markAsAllRead'])->name('notification.all-read')->middleware('auth:api');
Route::get('notification/{notification}/mark-single-as-read', [NotificationController::class, 'markSingleAsRead'])->name('notification.single-read')->middleware('auth:api');
Route::post('quote/{quote}/add-comment', [CommentController::class, 'store'])->name('comment.store')->middleware('auth:api');
Route::get('all-movies', [MovieController::class, 'showAllMovies'])->name('movies.all_show')->middleware('auth:api');
Route::post('add-quote', [QuoteController::class, 'storeWriteQuote'])->name('write.quote_store')->middleware('auth:api');

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
