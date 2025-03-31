<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route principale - page d'accueil
Route::get('/', [SpotifyController::class, 'index'])->name('home');

// Recherche
Route::get('/search', [SpotifyController::class, 'search'])->name('search');
Route::get('/search/{query}', [SpotifyController::class, 'search'])->name('search.query');

// Détails des pistes, albums, artistes
Route::get('/track/{id}', [SpotifyController::class, 'trackDetails'])->name('trackDetails');
Route::get('/album/{id}', [SpotifyController::class, 'albumDetails'])->name('albumDetails');
Route::get('/artist/{id}', [SpotifyController::class, 'artistDetails'])->name('artistDetails');
Route::get('/playlist/{id}', [SpotifyController::class, 'playlistDetails'])->name('playlistDetails');

// Bibliothèque de l'utilisateur
Route::get('/library', [SpotifyController::class, 'library'])->name('library');

// Routes pour les sections personnalisées
Route::get('/made-for-you', [SpotifyController::class, 'madeForYou'])->name('madeForYou');
Route::get('/recommendations', [SpotifyController::class, 'recommendations'])->name('recommendations');
Route::get('/new-releases', [SpotifyController::class, 'newReleases'])->name('newReleases');
Route::get('/featured-playlists', [SpotifyController::class, 'featuredPlaylists'])->name('featuredPlaylists');

// Routes pour simuler l'API Spotify (utilisées en AJAX)
Route::prefix('api')->group(function () {
    Route::get('/play-track/{id}', [SpotifyController::class, 'apiPlayTrack'])->name('api.playTrack');
    Route::get('/pause-track/{id}', [SpotifyController::class, 'apiPauseTrack'])->name('api.pauseTrack');
    Route::get('/like-track/{id}', [SpotifyController::class, 'apiLikeTrack'])->name('api.likeTrack');
    Route::get('/add-to-playlist/{trackId}/{playlistId}', [SpotifyController::class, 'apiAddToPlaylist'])->name('api.addToPlaylist');
});