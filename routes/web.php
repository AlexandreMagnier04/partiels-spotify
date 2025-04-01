<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes principales
Route::get('/', [SpotifyController::class, 'index'])->name('home');
Route::get('/search', [SpotifyController::class, 'search'])->name('search');
Route::get('/track/{id}', [SpotifyController::class, 'trackDetails'])->name('trackDetails');
Route::get('/album/{id}', [SpotifyController::class, 'albumDetails'])->name('albumDetails');
Route::get('/profile', [SpotifyController::class, 'profile'])->name('profile');
Route::get('/progression', [SpotifyController::class, 'progression'])->name('progression');
Route::get('/library', [SpotifyController::class, 'library'])->name('library');

// Routes pour le système SPOT'VIP
Route::post('/add-points', [SpotifyController::class, 'addPoints'])->name('addPoints');

// Routes pour simuler l'interaction avec l'API Spotify
Route::get('/artist/{id}', [SpotifyController::class, 'artistDetails'])->name('artistDetails');
Route::get('/playlist/{id}', [SpotifyController::class, 'playlistDetails'])->name('playlistDetails');
Route::get('/genre/{id}', [SpotifyController::class, 'genreDetails'])->name('genreDetails');
Route::get('/new-releases', [SpotifyController::class, 'newReleases'])->name('newReleases');
Route::get('/featured-playlists', [SpotifyController::class, 'featuredPlaylists'])->name('featuredPlaylists');
Route::get('/made-for-you', [SpotifyController::class, 'madeForYou'])->name('madeForYou');

// Fallback pour les routes non définies
Route::fallback(function () {
    return view('pages.error', ['message' => 'Cette page n\'existe pas.']);
});