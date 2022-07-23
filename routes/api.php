<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuoteController;
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

Route::middleware('guest')->group(function () {
	Route::post('register', [RegisterController::class, 'createUser'])->name('register');
	Route::post('login', [AuthController::class, 'login'])->name('login');

	Route::post('forgot-password', [ResetPasswordController::class, 'resetPassword'])->name('password.email');
	Route::post('reset-password/{token}', [ResetPasswordController::class, 'updatePassword'])->name('password.update');

	Route::post('successfully-verified/{token}', [RegisterController::class, 'verifyEmail'])->name('user.verify');

	Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
	Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

Route::middleware('auth:api')->group(function () {
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');

	Route::get('genres', [GenreController::class, 'showGenres'])->name('genres.get');

	Route::post('movies', [MovieController::class, 'store'])->name('movies.store');
	Route::get('all-movies', [MovieController::class, 'showAllMovies'])->name('movies.all_show');
	Route::get('user-movies', [MovieController::class, 'showUserMovies'])->name('user.movies');
	Route::post('movie/{movie}', [MovieController::class, 'showMovieDescription'])->name('movie.description');
	Route::get('movie/{movie}', [MovieController::class, 'showMovie'])->name('show.movie');
	Route::get('movie/{movie}/genres', [MovieController::class, 'showMovieWithGenres'])->name('movie.genres_show');
	Route::post('movie/{movie}/update', [MovieController::class, 'update'])->name('movie.update');
	Route::delete('movie/{movie}/delete', [MovieController::class, 'destroy'])->name('movie.destroy');

	Route::post('movie/{movie}/quote', [QuoteController::class, 'store'])->name('quote.store');
	Route::get('quote/{quote}', [QuoteController::class, 'showQuote'])->name('quote.create');
	Route::post('quote/{quote}/update', [QuoteController::class, 'update'])->name('quote.update');
	Route::delete('quote/{quote}/delete', [QuoteController::class, 'destroy'])->name('quote.destroy');
	Route::get('feed', [QuoteController::class, 'showPaginatedQuotes'])->name('all.quotes_show');
	Route::get('all-quotes', [QuoteController::class, 'showAllQuotes'])->name('quotes.all_show');
	Route::post('add-quote', [QuoteController::class, 'storeWriteQuote'])->name('write.quote_store');

	Route::post('quote/{quote}/add-comment', [CommentController::class, 'store'])->name('comment.store');

	Route::get('quote/{quote}/add-like', [LikeController::class, 'store'])->name('like.store');

	Route::get('notifications/mark-all-as-read', [NotificationController::class, 'markAsAllRead'])->name('notification.all-read');
	Route::get('notification/{notification}/mark-single-as-read', [NotificationController::class, 'markSingleAsRead'])->name('notification.single-read');
	Route::get('notifications', [NotificationController::class, 'show'])->name('notifications.show');
});
