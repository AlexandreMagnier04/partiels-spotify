<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\UserProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Playlist;

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
     * Affiche la page d'accueil avec le nouveau design 
     * Seule la méthode getSuggestedAlbums() est utilisée pour la section "Nouveautés"
     */
    public function index()
    {
        try {
            // Récupérer les albums suggérés pour la section "Nouveautés"
            $suggestedAlbums = $this->getSuggestedAlbums();
            
            return view('pages.home', [
                'userProgress' => $this->user_progress,
                'suggestedAlbums' => $suggestedAlbums
            ]);
        } catch (\Exception $e) {
            error_log('Erreur page accueil: ' . $e->getMessage());
            return view('pages.error', ['message' => 'Une erreur est survenue lors du chargement de la page d\'accueil.']);
        }
    }

    /**
     * Récupère les albums suggérés (nouveautés)
     */
    private function getSuggestedAlbums()
    {
        try {
            // On utilise les new releases pour les nouveautés
            $response = $this->client->request('GET', 'https://api.spotify.com/v1/browse/new-releases?limit=8', [
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
    // Créer des playlists fictives pour la page de profil
    $userPlaylists = [];
    $images = [
        '/img/playlist-1.png',
        '/img/playlist-2.png',
        '/img/playlist-3.png',
        '/img/playlist-7.png'
    ];
    
    for ($i = 1; $i <= 4; $i++) {
        $userPlaylists[] = new Playlist(
            'playlist-' . $i,
            'Playlist ' . $i,
            'Ma playlist numéro ' . $i,
            'Hugo',
            $images[$i-1],
            mt_rand(10, 100)
        );
    }
    
    return view('pages.profile', [
        'userProgress' => $this->user_progress,
        'userPlaylists' => $userPlaylists
    ]);
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
            
            // Créer l'objet Album complet
            $albumDetails = new Album(
                $objAlbum->id,
                $objAlbum->name,
                $artistName,
                $artistId,
                $frDate,
                $objAlbum->total_tracks,
                $objAlbum->images[0]->url,
                $objAlbum->tracks->items ?? [],
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
        
        return view('pages.search', [
            'query' => $query,
            'results' => [],
            'genres' => ['Pop', 'Hip Hop', 'Rock', 'Électronique', 'R&B', 'Indie', 'Jazz', 'Classique'],
            'userProgress' => $this->user_progress
        ]);
    }
}