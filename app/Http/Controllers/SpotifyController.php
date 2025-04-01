<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Playlist;
use App\Models\TrackDetails;
use App\Models\UserProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SpotifyController extends Controller
{
    private $client;
    private $api_token;
    private $client_id;
    private $client_secret;
    private $user_progress;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->client_id = '95e10ffab7914e01905037fad9a2cb4e';
        $this->client_secret = 'b4d93711efc643c5a092ece5e32e4598';
        // Obtenir un token d'accès
        $this->api_token = $this->getSpotifyToken();
        
        // Initialiser le progrès de l'utilisateur (dans un vrai projet, cela viendrait de la base de données)
        $this->user_progress = new UserProgress(
            'current_user',
            'Hugo',
            22000, // Points actuels
            100000, // Points maximum pour le niveau actuel
            16, // Niveau actuel
            [
                'listening_hours' => 150,
                'playlists_created' => 12,
                'exclusive_listens' => 5,
                'artist_interactions' => 20,
                'events_participated' => 2
            ],
            [
                ['level' => 5, 'reward' => 'Premium 1 mois offert', 'unlocked' => true],
                ['level' => 10, 'reward' => 'Accès anticipé aux sorties', 'unlocked' => true],
                ['level' => 15, 'reward' => 'Places pour concert exclusif', 'unlocked' => true],
                ['level' => 20, 'reward' => 'Rencontre avec artiste', 'unlocked' => false],
                ['level' => 25, 'reward' => 'Merchandising édition limitée', 'unlocked' => false]
            ]
        );
    }

    /**
     * Obtient un token d'accès Spotify
     */
    private function getSpotifyToken()
    {
        try {
            $response = $this->client->request('POST', 'https://accounts.spotify.com/api/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ],
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);
            
            $result = json_decode($response->getBody());
            return $result->access_token;
        } catch (\Exception $e) {
            error_log('Erreur lors de l\'obtention du token Spotify: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Affiche la page d'accueil avec le nouveau design à 2 colonnes
     */
    public function index()
    {
        try {
            // Récupérer les playlists de l'utilisateur
            $userPlaylists = $this->getUserPlaylists();
            
            // Récupérer les favoris de l'utilisateur
            $userFavorites = $this->getUserFavorites();
            
            // Récupérer les nouveautés
            $newReleases = $this->getNewReleases();
            
            // Récupérer les playlists mises en avant
            $featuredPlaylists = $this->getFeaturedPlaylists();
            
            return view('pages.home', [
                'userPlaylists' => $userPlaylists,
                'userFavorites' => $userFavorites,
                'newReleases' => $newReleases,
                'featuredPlaylists' => $featuredPlaylists,
                'userProgress' => $this->user_progress
            ]);
        } catch (\Exception $e) {
            error_log('Erreur page accueil: ' . $e->getMessage());
            return view('pages.error', ['message' => 'Une erreur est survenue lors du chargement de la page d\'accueil.']);
        }
    }

    /**
     * Affiche la page de progression SPOT'VIP
     */
    public function progression()
    {
        return view('pages.progression', [
            'userProgress' => $this->user_progress
        ]);
    }

    /**
     * Affiche la page de profil utilisateur avec le nouveau design
     */
    public function profile()
    {
        return view('pages.profile', [
            'userProgress' => $this->user_progress,
            'userPlaylists' => $this->getUserPlaylists(4) // Limite à 4 playlists
        ]);
    }

    /**
     * Récupère les playlists de l'utilisateur
     */
    private function getUserPlaylists($limit = 4)
    {
        // Dans une application réelle, on récupérerait les playlists de l'utilisateur via l'API
        // Mais pour cette démo, on va utiliser des données statiques similaires à l'écran 1
        $playlists = [];
        
        // Images pour les playlists en fonction du design de la deuxième capture d'écran
        $images = [
            '/img/covers/playlist-1.jpg',
            '/img/covers/playlist-2.jpg',
            '/img/covers/playlist-3.jpg',
            '/img/covers/playlist-4.jpg'
        ];
        
        for ($i = 1; $i <= $limit; $i++) {
            $playlists[] = new Playlist(
                'playlist-' . $i,
                'Playlist ' . $i,
                'Ma playlist numéro ' . $i,
                'Hugo',
                $images[$i-1],
                mt_rand(10, 100)
            );
        }
        
        return $playlists;
    }

    /**
     * Récupère les favoris de l'utilisateur
     */
    private function getUserFavorites()
    {
        // Pour la démo, on utilise des données statiques
        $favorites = [];
        
        // Images similaires à la capture d'écran 2
        $images = [
            '/img/covers/album1.jpg',
            '/img/covers/album2.jpg',
            '/img/covers/album3.jpg',
            '/img/covers/album4.jpg'
        ];
        
        $genres = ['Rap', 'Pop', 'Hip-hop', 'Électronique'];
        
        foreach ($images as $index => $image) {
            $favorites[] = new Album(
                'favorite-' . ($index + 1),
                'Album ' . ($index + 1),
                'Artiste ' . ($index + 1),
                'artist-' . ($index + 1),
                Carbon::now()->subDays(mt_rand(1, 365))->locale('fr')->translatedFormat('d F Y'),
                mt_rand(8, 16),
                $image,
                null,
                mt_rand(60, 90),
                [$genres[$index]]
            );
        }
        
        return $favorites;
    }

    /**
     * Récupère les nouveautés via l'API Spotify
     */
    private function getNewReleases()
    {
        try {
            $response = $this->client->request('GET', 'https://api.spotify.com/v1/browse/new-releases?limit=10', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ]
            ]);

            $res = $response->getBody();
            $objReleases = json_decode($res);
            
            $newReleases = [];
            foreach ($objReleases->albums->items as $album) {
                // Convertir la date de sortie en français
                $frDate = Carbon::parse($album->release_date)->locale('fr')->translatedFormat('d F Y');
                
                $newReleases[] = new Album(
                    $album->id,
                    $album->name,
                    $album->artists[0]->name,
                    $album->artists[0]->id,
                    $frDate,
                    $album->total_tracks,
                    $album->images[0]->url
                );
            }
            
            return $newReleases;
        } catch (\Exception $e) {
            error_log('Erreur API Spotify (getNewReleases): ' . $e->getMessage());
            
            // En cas d'erreur, utiliser des données de substitution
            $data = [
                ['id' => 'album1', 'name' => 'New Release 1', 'artist' => 'Artist 1', 'image' => '/img/covers/album1.jpg'],
                ['id' => 'album2', 'name' => 'New Release 2', 'artist' => 'Artist 2', 'image' => '/img/covers/album2.jpg'],
                ['id' => 'album3', 'name' => 'New Release 3', 'artist' => 'Artist 3', 'image' => '/img/covers/album3.jpg'],
                ['id' => 'album4', 'name' => 'New Release 4', 'artist' => 'Artist 4', 'image' => '/img/covers/album4.jpg']
            ];
            
            $newReleases = [];
            foreach ($data as $album) {
                $newReleases[] = new Album(
                    $album['id'],
                    $album['name'],
                    $album['artist'],
                    'artist-id-' . rand(1, 1000),
                    Carbon::now()->subDays(rand(1, 30))->locale('fr')->translatedFormat('d F Y'),
                    rand(8, 16),
                    $album['image']
                );
            }
            
            return $newReleases;
        }
    }

    /**
     * Récupère les playlists mises en avant via l'API Spotify
     */
    private function getFeaturedPlaylists()
    {
        try {
            $response = $this->client->request('GET', 'https://api.spotify.com/v1/browse/featured-playlists?limit=10', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ]
            ]);

            $res = $response->getBody();
            $objPlaylists = json_decode($res);
            
            $featuredPlaylists = [];
            foreach ($objPlaylists->playlists->items as $playlist) {
                $featuredPlaylists[] = new Playlist(
                    $playlist->id,
                    $playlist->name,
                    $playlist->description ?? '',
                    $playlist->owner->display_name,
                    $playlist->images[0]->url,
                    $playlist->tracks->total
                );
            }
            
            return $featuredPlaylists;
        } catch (\Exception $e) {
            error_log('Erreur API Spotify (getFeaturedPlaylists): ' . $e->getMessage());
            
            // En cas d'erreur, utiliser des données de substitution
            $data = [
                ['id' => 'playlist1', 'name' => 'Featured Playlist 1', 'image' => '/img/covers/playlist-1.jpg'],
                ['id' => 'playlist2', 'name' => 'Featured Playlist 2', 'image' => '/img/covers/playlist-2.jpg'],
                ['id' => 'playlist3', 'name' => 'Featured Playlist 3', 'image' => '/img/covers/playlist-3.jpg'],
                ['id' => 'playlist4', 'name' => 'Featured Playlist 4', 'image' => '/img/covers/playlist-4.jpg']
            ];
            
            $featuredPlaylists = [];
            foreach ($data as $playlist) {
                $featuredPlaylists[] = new Playlist(
                    $playlist['id'],
                    $playlist['name'],
                    'Playlist mise en avant selon vos goûts',
                    'Spotify',
                    $playlist['image'],
                    rand(20, 50)
                );
            }
            
            return $featuredPlaylists;
        }
    }

    /**
     * Affiche les détails d'une piste
     */
    public function trackDetails($id)
    {
        try {
            // Appel à l'API Spotify pour obtenir les détails de la piste
            $response = $this->client->request('GET', 'https://api.spotify.com/v1/tracks/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ]
            ]);

            $res = $response->getBody();
            $objTrack = json_decode($res);
            
            // Récupérer les features audio de la piste (danceability, energy, etc.)
            $audioFeaturesResponse = $this->client->request('GET', 'https://api.spotify.com/v1/audio-features/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ]
            ]);
            
            $audioFeaturesRes = $audioFeaturesResponse->getBody();
            $objAudioFeatures = json_decode($audioFeaturesRes);
            
            // Récupérer les détails de l'album
            $albumResponse = $this->client->request('GET', 'https://api.spotify.com/v1/albums/' . $objTrack->album->id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ]
            ]);
            
            $albumRes = $albumResponse->getBody();
            $objAlbum = json_decode($albumRes);
            
            // Convertir la date de sortie en français
            $frDate = Carbon::parse($objAlbum->release_date)->locale('fr')->translatedFormat('d F Y');
            
            // Récupérer les artistes
            $artists = [];
            foreach ($objTrack->artists as $artist) {
                $artists[] = $artist->name;
            }
            
            // Créer l'objet TrackDetails
            $trackDetails = new TrackDetails(
                $objTrack->id,
                $objTrack->name,
                $artists,
                $objTrack->album->name,
                $objTrack->album->id,
                $frDate,
                $objTrack->duration_ms,
                $objTrack->popularity,
                $objAudioFeatures->danceability,
                $objAudioFeatures->energy,
                $objAudioFeatures->tempo,
                $objAudioFeatures->key,
                $objAudioFeatures->mode,
                $objTrack->preview_url,
                $objTrack->album->images[0]->url
            );
            
            // Ajouter des SPOINTS pour l'écoute (dans un cas réel, vérifier si c'est la première écoute)
            $this->user_progress->addPoints(10, 'Écoute de titre');
            
            return view('pages.track-details', [
                'trackDetails' => $trackDetails,
                'userProgress' => $this->user_progress
            ]);
        } catch (\Exception $e) {
            error_log('Erreur API Spotify (trackDetails): ' . $e->getMessage());
            return view('pages.error', ['message' => 'Impossible de charger les détails de ce titre.']);
        }
    }
    
    /**
     * Affiche les détails d'un album
     */
    public function albumDetails($id)
    {
        try {
            // Appel à l'API Spotify pour obtenir les détails de l'album
            $response = $this->client->request('GET', 'https://api.spotify.com/v1/albums/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ]
            ]);

            $res = $response->getBody();
            $objAlbum = json_decode($res);
            
            // Convertir la date de sortie en français
            $frDate = Carbon::parse($objAlbum->release_date)->locale('fr')->translatedFormat('d F Y');
            
            // Récupérer l'artiste principal
            $artistName = $objAlbum->artists[0]->name;
            $artistId = $objAlbum->artists[0]->id;
            
            // Créer la liste des pistes
            $tracks = [];
            foreach ($objAlbum->tracks->items as $track) {
                $tracks[] = new Track(
                    $track->id,
                    $track->name,
                    $track->artists[0]->name,
                    $track->artists[0]->id,
                    $track->duration_ms,
                    $track->preview_url
                );
            }
            
            // Créer l'objet Album complet
            $albumDetails = new Album(
                $objAlbum->id,
                $objAlbum->name,
                $artistName,
                $artistId,
                $frDate,
                $objAlbum->total_tracks,
                $objAlbum->images[0]->url,
                $tracks,
                $objAlbum->popularity,
                $objAlbum->genres ?? [],
                $objAlbum->label ?? '',
                $objAlbum->copyrights[0]->text ?? ''
            );
            
            // Ajouter des SPOINTS pour la consultation d'album
            $this->user_progress->addPoints(25, 'Consultation d\'album');
            
            return view('pages.album-details', [
                'albumDetails' => $albumDetails,
                'userProgress' => $this->user_progress
            ]);
        } catch (\Exception $e) {
            error_log('Erreur API Spotify (albumDetails): ' . $e->getMessage());
            return view('pages.error', ['message' => 'Impossible de charger les détails de cet album.']);
        }
    }

    /**
     * Recherche dans Spotify
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $results = [];
        
        if ($query) {
            try {
                $response = $this->client->request('GET', 'https://api.spotify.com/v1/search', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->api_token,
                        'Accept' => 'application/json',
                    ],
                    'query' => [
                        'q' => $query,
                        'type' => 'album,artist,track,playlist',
                        'limit' => 5
                    ]
                ]);

                $res = $response->getBody();
                $objResults = json_decode($res);
                
                // Transformer les résultats en objets de nos modèles
                $results = [
                    'tracks' => $this->transformTracks($objResults->tracks->items ?? []),
                    'albums' => $this->transformAlbums($objResults->albums->items ?? []),
                    'artists' => $this->transformArtists($objResults->artists->items ?? []),
                    'playlists' => $this->transformPlaylists($objResults->playlists->items ?? [])
                ];
                
                // Ajouter des SPOINTS pour la recherche
                $this->user_progress->addPoints(5, 'Recherche effectuée');
            } catch (\Exception $e) {
                error_log('Erreur API Spotify (search): ' . $e->getMessage());
                $results = [];
            }
        }
        
        return view('pages.search', [
            'query' => $query,
            'results' => $results,
            'genres' => $this->getGenresList(),
            'userProgress' => $this->user_progress
        ]);
    }
    
    /**
     * Transformation des pistes depuis l'API
     */
    private function transformTracks($tracks)
    {
        $transformed = [];
        foreach ($tracks as $track) {
            $transformed[] = new Track(
                $track->id,
                $track->name,
                $track->artists[0]->name,
                $track->artists[0]->id,
                $track->duration_ms,
                $track->preview_url,
                $track->album->images[0]->url ?? null,
                $track->album->name,
                $track->album->id
            );
        }
        return $transformed;
    }
    
    /**
     * Transformation des albums depuis l'API
     */
    private function transformAlbums($albums)
    {
        $transformed = [];
        foreach ($albums as $album) {
            // Convertir la date en français
            $frDate = Carbon::parse($album->release_date)->locale('fr')->translatedFormat('d F Y');
            
            $transformed[] = new Album(
                $album->id,
                $album->name,
                $album->artists[0]->name,
                $album->artists[0]->id,
                $frDate,
                $album->total_tracks,
                $album->images[0]->url ?? null
            );
        }
        return $transformed;
    }
    
    /**
     * Transformation des artistes depuis l'API
     */
    private function transformArtists($artists)
    {
        $transformed = [];
        foreach ($artists as $artist) {
            $transformed[] = new Artist(
                $artist->id,
                $artist->name,
                $artist->followers->total,
                $artist->popularity,
                $artist->genres ?? [],
                $artist->images[0]->url ?? null
            );
        }
        return $transformed;
    }
    
    /**
     * Transformation des playlists depuis l'API
     */
    private function transformPlaylists($playlists)
    {
        $transformed = [];
        foreach ($playlists as $playlist) {
            $transformed[] = new Playlist(
                $playlist->id,
                $playlist->name,
                $playlist->description ?? '',
                $playlist->owner->display_name,
                $playlist->images[0]->url ?? null,
                $playlist->tracks->total
            );
        }
        return $transformed;
    }

    /**
     * Récupération des genres
     */
    private function getGenresList()
    {
        try {
            $response = $this->client->request('GET', 'https://api.spotify.com/v1/recommendations/available-genre-seeds', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ]
            ]);
            
            $res = $response->getBody();
            $genres = json_decode($res)->genres;
            return $genres;
        } catch (\Exception $e) {
            error_log('Erreur récupération genres: ' . $e->getMessage());
            return [
                'Pop', 'Rock', 'Hip-Hop', 'R&B', 'Electronic', 'Dance', 
                'Jazz', 'Classical', 'Country', 'Folk', 'Metal', 'Punk', 
                'Indie', 'Alternative', 'Reggae', 'Blues', 'Soul'
            ];
        }
    }

    /**
     * Ajouter des points SPOT'VIP
     */
    public function addPoints(Request $request)
    {
        $action = $request->input('action');
        $points = $request->input('points', 0);
        
        $this->user_progress->addPoints($points, $action);
        
        return redirect()->back()->with('message', "Vous avez gagné $points SPOINTS pour l'action : $action");
    }
    
    /**
     * Simuler des actions pour gagner des points (pour la démonstration)
     */
    public function simulateAction(Request $request, $action)
    {
        $points = 0;
        $actionLabel = '';
        
        switch ($action) {
            case 'listen':
                $points = 10;
                $actionLabel = 'Écoute de musique (1 heure)';
                break;
            case 'playlist':
                $points = 50;
                $actionLabel = 'Création et partage d\'une playlist';
                break;
            case 'preview':
                $points = 100;
                $actionLabel = 'Écoute d\'un album en avant-première';
                break;
            case 'event':
                $points = 200;
                $actionLabel = 'Participation à un événement Spotify';
                break;
            case 'interaction':
                $points = 25;
                $actionLabel = 'Interaction avec un artiste';
                break;
            default:
                $points = 5;
                $actionLabel = 'Action générique';
        }
        
        $this->user_progress->addPoints($points, $actionLabel);
        
        return redirect()->back()->with('success', "Vous avez gagné $points SPOINTS pour l'action : $actionLabel");
    }
    
    /**
     * Page de bibliothèque (simplifiée pour la démo)
     */
    public function library()
    {
        return view('pages.library', [
            'userPlaylists' => $this->getUserPlaylists(8),
            'userFavorites' => $this->getUserFavorites(),
            'userProgress' => $this->user_progress
        ]);
    }
}