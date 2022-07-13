<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
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

Route::post('movies', [MovieController::class, 'store'])->name('movies.store');

Route::get('genres', [GenreController::class, 'getGenres'])->name('genres.get')->middleware('auth:api');
Route::get('feed', [MovieController::class, 'getAllMovies'])->name('movies.create')->middleware('auth:api');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:api');
Route::get('user-movies', [MovieController::class, 'getUserMovies'])->name('user.movies')->middleware('auth:api');
Route::post('movie/{id}', [MovieController::class, 'getMovieDescription'])->name('movie.description')->middleware('auth:api');
