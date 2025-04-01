<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Playlist;
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
            
            // Récupérer les playlists suggérées (basées sur les goûts de l'utilisateur)
            $suggestedPlaylists = $this->getSuggestedPlaylists();
            
            // Récupérer les albums suggérés (basés sur les goûts de l'utilisateur)
            $suggestedAlbums = $this->getSuggestedAlbums();
            
            return view('pages.home', [
                'userPlaylists' => $userPlaylists,
                'userFavorites' => $userFavorites,
                'suggestedPlaylists' => $suggestedPlaylists,
                'suggestedAlbums' => $suggestedAlbums,
                'userProgress' => $this->user_progress
            ]);
        } catch (\Exception $e) {
            error_log('Erreur page accueil: ' . $e->getMessage());
            return view('pages.error', ['message' => 'Une erreur est survenue lors du chargement de la page d\'accueil.']);
        }
    }

    /**
     * Récupère les playlists de l'utilisateur (simulé)
     */
    private function getUserPlaylists($limit = 4)
    {
        // Dans une application réelle, on récupérerait les playlists de l'utilisateur via l'API
        $playlists = [];
        
        // Images pour les playlists
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
     * Récupère les favoris de l'utilisateur (simulé)
     */
    private function getUserFavorites()
    {
        // Pour la démo, on utilise des données statiques
        $favorites = [];
        
        // Images d'albums
        $images = [
            '/img/covers/album1.jpg',
            '/img/covers/album2.jpg',
            '/img/covers/album3.jpg',
            '/img/covers/album4.jpg'
        ];
        
        $artists = ['Artiste 1', 'Artiste 2', 'Artiste 3', 'Artiste 4'];
        $genres = ['Rap', 'Pop', 'Hip-hop', 'Électronique'];
        
        foreach ($images as $index => $image) {
            $favorites[] = new Album(
                'favorite-' . ($index + 1),
                'Album ' . ($index + 1),
                $artists[$index],
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
     * Récupère les playlists suggérées (simulé)
     */
    private function getSuggestedPlaylists()
    {
        try {
            // On utilise les featured playlists pour simuler les suggestions
            $response = $this->client->request('GET', 'https://api.spotify.com/v1/browse/featured-playlists?limit=4', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ]
            ]);

            $res = $response->getBody();
            $objPlaylists = json_decode($res);
            
            $suggestedPlaylists = [];
            foreach ($objPlaylists->playlists->items as $playlist) {
                $suggestedPlaylists[] = new Playlist(
                    $playlist->id,
                    $playlist->name,
                    $playlist->description ?? '',
                    $playlist->owner->display_name,
                    $playlist->images[0]->url,
                    $playlist->tracks->total
                );
            }
            
            return $suggestedPlaylists;
        } catch (\Exception $e) {
            error_log('Erreur API Spotify (getSuggestedPlaylists): ' . $e->getMessage());
            
            // En cas d'erreur, utiliser des données de substitution
            $data = [
                ['id' => 'playlist1', 'name' => 'Mix Hip-Hop', 'image' => '/img/covers/playlist-1.jpg'],
                ['id' => 'playlist2', 'name' => 'Nouveautés Pop', 'image' => '/img/covers/playlist-2.jpg'],
                ['id' => 'playlist3', 'name' => 'Classiques Rap', 'image' => '/img/covers/playlist-3.jpg'],
                ['id' => 'playlist4', 'name' => 'Électro Mix', 'image' => '/img/covers/playlist-4.jpg']
            ];
            
            $suggestedPlaylists = [];
            foreach ($data as $playlist) {
                $suggestedPlaylists[] = new Playlist(
                    $playlist['id'],
                    $playlist['name'],
                    'Playlist suggérée selon vos goûts',
                    'Spotify',
                    $playlist['image'],
                    rand(20, 50)
                );
            }
            
            return $suggestedPlaylists;
        }
    }

    /**
     * Récupère les albums suggérés (simulé)
     */
    private function getSuggestedAlbums()
    {
        try {
            // On utilise les new releases pour simuler les suggestions
            $response = $this->client->request('GET', 'https://api.spotify.com/v1/browse/new-releases?limit=4', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ]
            ]);

            $res = $response->getBody();
            $objReleases = json_decode($res);
            
            $suggestedAlbums = [];
            foreach ($objReleases->albums->items as $album) {
                // Convertir la date de sortie en français
                $frDate = Carbon::parse($album->release_date)->locale('fr')->translatedFormat('d F Y');
                
                $suggestedAlbums[] = new Album(
                    $album->id,
                    $album->name,
                    $album->artists[0]->name,
                    $album->artists[0]->id,
                    $frDate,
                    $album->total_tracks,
                    $album->images[0]->url
                );
            }
            
            return $suggestedAlbums;
        } catch (\Exception $e) {
            error_log('Erreur API Spotify (getSuggestedAlbums): ' . $e->getMessage());
            
            // En cas d'erreur, utiliser des données de substitution
            $data = [
                ['id' => 'album1', 'name' => 'Album Rap 2024', 'artist' => 'Artiste Rap', 'image' => '/img/covers/album1.jpg'],
                ['id' => 'album2', 'name' => 'Hits Pop', 'artist' => 'Artiste Pop', 'image' => '/img/covers/album2.jpg'],
                ['id' => 'album3', 'name' => 'Électro Mix', 'artist' => 'DJ Électro', 'image' => '/img/covers/album3.jpg'],
                ['id' => 'album4', 'name' => 'Hip-Hop 2024', 'artist' => 'Artiste Hip-Hop', 'image' => '/img/covers/album4.jpg']
            ];
            
            $suggestedAlbums = [];
            foreach ($data as $album) {
                $suggestedAlbums[] = new Album(
                    $album['id'],
                    $album['name'],
                    $album['artist'],
                    'artist-id-' . rand(1, 1000),
                    Carbon::now()->subDays(rand(1, 30))->locale('fr')->translatedFormat('d F Y'),
                    rand(8, 16),
                    $album['image']
                );
            }
            
            return $suggestedAlbums;
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
     * Affiche la page de profil utilisateur
     */
    public function profile()
    {
        return view('pages.profile', [
            'userProgress' => $this->user_progress,
            'userPlaylists' => $this->getUserPlaylists(4)
        ]);
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
            
            // Récupérer les features audio de la piste
            $audioFeaturesResponse = $this->client->request('GET', 'https://api.spotify.com/v1/audio-features/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ]
            ]);
            
            $audioFeaturesRes = $audioFeaturesResponse->getBody();
            $objAudioFeatures = json_decode($audioFeaturesRes);
            
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
                Carbon::parse($objTrack->album->release_date)->locale('fr')->translatedFormat('d F Y'),
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
            
            // Ajouter des SPOINTS pour l'écoute
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
            } catch (\Exception $e) {
                error_log('Erreur API Spotify (search): ' . $e->getMessage());
                $results = [];
            }
        }
        
        return view('pages.search', [
            'query' => $query,
            'results' => $results,
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
}