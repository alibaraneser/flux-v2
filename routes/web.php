<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpotifyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');;
    Route::get('/test', 'HomeController@test');

    Route::get('/searchTrack', [SpotifyController::class, 'searchTrack'])->name('searchTrack');

    Route::get('/genre/{id}', [HomeController::class, 'genre'])->name('genre');
    Route::get('/track/{id}', [HomeController::class, 'track'])->name('track');
    Route::get('/artist/{id}', [HomeController::class, 'artist'])->name('artist');
    Route::get('/genres/{id}', [HomeController::class, 'genres'])->name('genres');
    Route::get('/genreList', [HomeController::class, 'genreList'])->name('genreList');
    Route::get('/search/{key}', [HomeController::class, 'search'])->name('search');
    Route::get('/genre/remove/{id}', [HomeController::class, 'removeGenre'])->name('genre.remove');
    Route::get('/genre/add/{id}', [HomeController::class, 'addGenre'])->name('genre.add');
    Route::post('/updateArtist', [HomeController::class, 'updateArtist'])->name('updateArtist');
    Route::get('/cities', [HomeController::class, 'cities'])->name('cities');
    Route::get('/list', [HomeController::class, 'list'])->name('list');

    Route::get('/logout', [DashboardController::class, 'logout'])->name('logout');
    Route::get('/login', [DashboardController::class, 'signin'])->name('signin');

    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login', [DashboardController::class, 'login']);
        Route::post('/post', [DashboardController::class, 'signin'])->name("signin");
    });
});
