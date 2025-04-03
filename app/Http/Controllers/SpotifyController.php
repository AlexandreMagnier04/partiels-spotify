<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Playlist;
use App\Models\TrackDetails;
use App\Models\UserProgress;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotifyController extends Controller
{
    private $client;
    private $api_token;
    private $client_id;
    private $client_secret;
    private $user_progress;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'verify' => false 
        ]);
        $this->client_id = 'a8f2de6dc1014b4a93e817594dd2b9bb';
        $this->client_secret = '27be8333e70146b697bccc7bb95d2541';
        $this->api_token = $this->getSpotifyToken();
        

        $this->initUserProgress();
    }

    //Initialiser les données de progression de l'utilisateur
    private function initUserProgress()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'Hugo';
        $user->email = 'hugo@exemple.com';
        
        $this->user_progress = $user->getProgressData();
    }

    //Obtenir un token d'accès Spotify
     
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
                ],
            ]);
            
            $result = json_decode($response->getBody());
            return $result->access_token;
        } catch (\Exception $e) {
            error_log('Erreur lors de l\'obtention du token Spotify: ' . $e->getMessage());
            return '';
        }
    }

    //Affiche la page d'accueil

    public function index()
    {
        try {
            // Récupérer les albums suggérés pour la section "Nouveautés"
            $suggestedAlbums = $this->getSuggestedAlbums();
            
            // Récupérer les playlists de l'utilisateur et les playlists suggérées
            $userPlaylists = $this->getUserPlaylists(4);
            $suggestedPlaylists = $this->getSuggestedPlaylists(4);
            
            return view('pages.home', [
                'userProgress' => $this->user_progress,
                'suggestedAlbums' => $suggestedAlbums,
                'userPlaylists' => $userPlaylists,
                'suggestedPlaylists' => $suggestedPlaylists
            ]);
        } catch (\Exception $e) {
            error_log('Erreur page accueil: ' . $e->getMessage());
            return view('pages.error', ['message' => 'Une erreur est survenue lors du chargement de la page d\'accueil.']);
        }
    }

    //Récupère les albums suggérés (nouveautés)
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

    //Affiche la page de progression SPOT'VIP

    public function progression()
    {
        return view('pages.progression', [
            'userProgress' => $this->user_progress
        ]);
    }

    //Récupère les playlists de l'utilisateur
     
    private function getUserPlaylists($limit = 4)
    {
        // Liste des images pour les playlists de l'utilisateur
        $images = [
            '/img/playlist-1.png',
            '/img/playlist-2.png',
            '/img/playlist-7.png',
            '/img/playlist-3.png',
            '/img/playlist-4.png',
            '/img/playlist-5.png',
            '/img/playlist-6.png',
            '/img/playlist-8.png'
        ];
        
        $names = [
            'Mes hits du moment',
            'Concentration',
            'Soirée Summer',
            'Running Motivation',
            'Chill & Relax',
            'Hip-Hop Français',
            'Électro Dance',
            'Classics'
        ];
        
        $descriptions = [
            'Les titres que j\'écoute en boucle actuellement',
            'Musique parfaite pour travailler et rester concentré',
            'Playlist idéale pour l\'été et les soirées entre amis',
            'Motivation garantie pour vos séances de sport',
            'Ambiance détendue pour se relaxer',
            'Le meilleur du hip-hop français',
            'Sons électro parfaits pour danser',
            'Les grands classiques intemporels'
        ];
        
        $playlists = [];
        for ($i = 0; $i < min($limit, count($images)); $i++) {
            $trackCount = mt_rand(15, 50);
            $playlists[] = new Playlist(
                'user-playlist-' . ($i + 1),
                $names[$i],
                $descriptions[$i],
                'Hugo',
                $images[$i],
                $trackCount
            );
        }
        
        return $playlists;
    }

    //Récupère les playlists suggérées pour l'utilisateur
     
    private function getSuggestedPlaylists($limit = 4)
    {
        try {
            // Essayer de récupérer les playlists mises en avant via l'API
            $response = $this->client->request('GET', 'https://api.spotify.com/v1/browse/featured-playlists?limit=' . $limit, [
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
            $images = [
                '/img/playlist-4.png',
                '/img/playlist-5.png', 
                '/img/playlist-6.png',
                '/img/playlist-8.png'
            ];
            
            $names = [
                'Découvertes de la semaine',
                'Hit Parade 2024',
                'Viral Hits',
                'Ambiance Chill'
            ];
            
            $descriptions = [
                'Nouvelles sorties adaptées à vos goûts',
                'Les titres les plus écoutés du moment',
                'Les hits qui font le buzz sur les réseaux',
                'Une ambiance détendue pour se relaxer'
            ];
            
            $suggestedPlaylists = [];
            for ($i = 0; $i < min($limit, count($images)); $i++) {
                $trackCount = mt_rand(15, 50);
                $suggestedPlaylists[] = new Playlist(
                    'suggested-playlist-' . ($i + 1),
                    $names[$i],
                    $descriptions[$i],
                    'Spotify',
                    $images[$i],
                    $trackCount
                );
            }
            
            return $suggestedPlaylists;
        }
    }

    // Génère une liste de pistes simulées pour une playlist
     
    private function generatePlaylistTracks($count = 20)
    {
        $trackNames = [
            'Bohemian Rhapsody', 'Imagine', 'Billie Jean', 'Hotel California', 'Sweet Child O\' Mine',
            'Smells Like Teen Spirit', 'Yesterday', 'Let It Be', 'Purple Haze', 'Like a Rolling Stone',
            'Stairway to Heaven', 'I Want to Hold Your Hand', 'Hey Jude', 'My Generation', 'Respect',
            'Johnny B. Goode', 'Good Vibrations', 'London Calling', 'What\'s Going On', 'Waterloo Sunset',
            'God Save the Queen', 'Gimme Shelter', 'Superstition', 'Blitzkrieg Bop', 'Heroes',
            'Born to Run', 'I Wanna Be Sedated', 'Anarchy in the U.K.', 'Thriller', 'Sweet Dreams'
        ];
        
        $artistNames = [
            'Queen', 'John Lennon', 'Michael Jackson', 'Eagles', 'Guns N\' Roses',
            'Nirvana', 'The Beatles', 'The Beatles', 'Jimi Hendrix', 'Bob Dylan',
            'Led Zeppelin', 'The Beatles', 'The Beatles', 'The Who', 'Aretha Franklin',
            'Chuck Berry', 'The Beach Boys', 'The Clash', 'Marvin Gaye', 'The Kinks',
            'Sex Pistols', 'The Rolling Stones', 'Stevie Wonder', 'Ramones', 'David Bowie',
            'Bruce Springsteen', 'Ramones', 'Sex Pistols', 'Michael Jackson', 'Eurythmics'
        ];
        
        $albumNames = [
            'A Night at the Opera', 'Imagine', 'Thriller', 'Hotel California', 'Appetite for Destruction',
            'Nevermind', 'Help!', 'Let It Be', 'Are You Experienced', 'Highway 61 Revisited',
            'Led Zeppelin IV', 'Meet the Beatles!', 'Let It Be', 'My Generation', 'I Never Loved a Man the Way I Love You',
            'Chuck Berry Is on Top', 'Pet Sounds', 'London Calling', 'What\'s Going On', 'Something Else',
            'Never Mind the Bollocks', 'Let It Bleed', 'Talking Book', 'Ramones', 'Heroes',
            'Born to Run', 'Road to Ruin', 'Never Mind the Bollocks', 'Thriller', 'Sweet Dreams'
        ];
        
        $tracks = [];
        
        $count = min($count, count($trackNames));
        
        for ($i = 0; $i < $count; $i++) {
            // Durée aléatoire entre 2:30 et 5:00
            $durationMs = rand(150, 300) * 1000;
            
            $tracks[] = new Track(
                'track-' . ($i + 1),
                $trackNames[$i],
                $artistNames[$i],
                'artist-' . ($i + 1),
                $durationMs,
                null,
                '/img/covers/album' . (($i % 4) + 1) . '.jpg',
                $albumNames[$i],
                'album-' . ($i + 1)
            );
        }
        
        return $tracks;
    }

    //Affiche les détails d'une playlist
     
    public function playlistDetails($id)
    {
        try {
            // Essayer de récupérer les détails de la playlist via l'API
            if (strpos($id, 'user-playlist-') === 0) {
                // C'est une playlist de l'utilisateur
                $userPlaylists = $this->getUserPlaylists(8);
                foreach ($userPlaylists as $playlist) {
                    if ($playlist->getId() === $id) {
                        // Générer des pistes simulées pour la playlist
                        $tracks = $this->generatePlaylistTracks($playlist->getTracksCount());
                        
                        // Calculer la durée totale
                        $totalMs = 0;
                        foreach ($tracks as $track) {
                            // Vérifier si la méthode getDurationMs existe, sinon utiliser la durée en propriété
                            if (method_exists($track, 'getDurationMs')) {
                                $totalMs += $track->getDurationMs();
                            } else if (property_exists($track, 'duration_ms')) {
                                $totalMs += $track->duration_ms;
                            } else {
                                // Durée par défaut si aucune méthode ou propriété n'est disponible
                                $totalMs += 210000; // 3:30 minutes par défaut
                            }
                        }
                        
                        // Formater la durée totale
                        $hours = floor($totalMs / 3600000);
                        $minutes = floor(($totalMs % 3600000) / 60000);
                        
                        if ($hours > 0) {
                            $totalDuration = $hours . ' h ' . $minutes . ' min';
                        } else {
                            $totalDuration = $minutes . ' min';
                        }
                        
                        // Ajouter des points SPOT'VIP pour consulter une playlist
                        $this->user_progress->addPoints(5, 'Consultation de playlist');
                        
                        return view('pages.playlist-details', [
                            'playlist' => $playlist,
                            'tracks' => $tracks,
                            'totalDuration' => $totalDuration,
                            'userProgress' => $this->user_progress
                        ]);
                    }
                }
            } else if (strpos($id, 'suggested-playlist-') === 0) {
                // C'est une playlist suggérée
                $suggestedPlaylists = $this->getSuggestedPlaylists(8);
                foreach ($suggestedPlaylists as $playlist) {
                    if ($playlist->getId() === $id) {
                        // Générer des pistes simulées pour la playlist
                        $tracks = $this->generatePlaylistTracks($playlist->getTracksCount());
                        
                        // Calculer la durée totale
                        $totalMs = 0;
                        foreach ($tracks as $track) {
                            // Vérifier si la méthode getDurationMs existe, sinon utiliser la durée en propriété
                            if (method_exists($track, 'getDurationMs')) {
                                $totalMs += $track->getDurationMs();
                            } else if (property_exists($track, 'duration_ms')) {
                                $totalMs += $track->duration_ms;
                            } else {
                                // Durée par défaut si aucune méthode ou propriété n'est disponible
                                $totalMs += 210000; // 3:30 minutes par défaut
                            }
                        }
                        
                        $hours = floor($totalMs / 3600000);
                        $minutes = floor(($totalMs % 3600000) / 60000);
                        
                        if ($hours > 0) {
                            $totalDuration = $hours . ' h ' . $minutes . ' min';
                        } else {
                            $totalDuration = $minutes . ' min';
                        }
                        
                        // Ajouter des points SPOT'VIP pour consulter une playlist
                        $this->user_progress->addPoints(5, 'Consultation de playlist');
                        
                        return view('pages.playlist-details', [
                            'playlist' => $playlist,
                            'tracks' => $tracks,
                            'totalDuration' => $totalDuration,
                            'userProgress' => $this->user_progress
                        ]);
                    }
                }
            } else {
                // C'est une playlist Spotify, on essaie de la récupérer via l'API
                $response = $this->client->request('GET', 'https://api.spotify.com/v1/playlists/' . $id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->api_token,
                        'Accept' => 'application/json',
                    ]
                ]);
                
                $res = $response->getBody();
                $objPlaylist = json_decode($res);
                
                $playlist = new Playlist(
                    $objPlaylist->id,
                    $objPlaylist->name,
                    $objPlaylist->description ?? '',
                    $objPlaylist->owner->display_name,
                    $objPlaylist->images[0]->url ?? '/img/covers/playlist-1.jpg',
                    $objPlaylist->tracks->total
                );
                
                // Récupérer les pistes de la playlist
                $tracks = [];
                
                foreach ($objPlaylist->tracks->items as $item) {
                    if ($item->track) {
                        $track = $item->track;
                        
                        $tracks[] = new Track(
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
                }
                
                $totalMs = 0;
                foreach ($tracks as $track) {
                    // Vérifier si la méthode getDurationMs existe, sinon utiliser la durée en propriété
                    if (method_exists($track, 'getDurationMs')) {
                        $totalMs += $track->getDurationMs();
                    } else if (property_exists($track, 'duration_ms')) {
                        $totalMs += $track->duration_ms;
                    } else {
                        // Durée par défaut si aucune méthode ou propriété n'est disponible
                        $totalMs += 210000; // 3:30 minutes par défaut
                    }
                }
                
                // Formater la durée totale
                $hours = floor($totalMs / 3600000);
                $minutes = floor(($totalMs % 3600000) / 60000);
                
                if ($hours > 0) {
                    $totalDuration = $hours . ' h ' . $minutes . ' min';
                } else {
                    $totalDuration = $minutes . ' min';
                }
                
                // Ajouter des points SPOT'VIP pour consulter une playlist
                $this->user_progress->addPoints(5, 'Consultation de playlist');
                
                return view('pages.playlist-details', [
                    'playlist' => $playlist,
                    'tracks' => $tracks,
                    'totalDuration' => $totalDuration,
                    'userProgress' => $this->user_progress
                ]);
            }
            
            // Si on arrive ici, c'est que la playlist n'a pas été trouvée
            return view('pages.error', ['message' => 'Playlist introuvable.']);
        } catch (\Exception $e) {
            error_log('Erreur lors de la récupération des détails de la playlist: ' . $e->getMessage());
            return view('pages.error', ['message' => 'Impossible de charger les détails de cette playlist.']);
        }
    }


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
 * Affiche les résultats de recherche ou la page d'exploration si aucune requête n'est spécifiée
 * 
 * @param Request $request
 * @return \Illuminate\View\View
 */

public function search(Request $request)
{
    $query = $request->input('q', '');
    
    if (!empty($query)) {
        try {
            // Initialiser les tableaux de résultats
            $results = [
                'tracks' => [],
                'artists' => [],
                'albums' => [],
                'playlists' => []
            ];
            
            // Effectuer la recherche via l'API Spotify
            $response = $this->client->request('GET', 'https://api.spotify.com/v1/search', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ],
                'query' => [
                    'q' => $query,
                    'type' => 'track,artist,album,playlist',
                    'limit' => 10
                ],
                'verify' => false 
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            // Traiter les pistes
            if (isset($data['tracks']['items'])) {
                foreach ($data['tracks']['items'] as $item) {
                    $results['tracks'][] = new Track(
                        $item['id'],
                        $item['name'],
                        $item['artists'][0]['name'],
                        $item['artists'][0]['id'],
                        $item['duration_ms'],
                        $item['preview_url'] ?? null,
                        isset($item['album']['images'][0]) ? $item['album']['images'][0]['url'] : null,
                        $item['album']['name'],
                        $item['album']['id']
                    );
                }
            }
            
            // Pour les artistes dans la recherche API
                if (isset($data['artists']['items'])) {
                    foreach ($data['artists']['items'] as $item) {
                        $artist = new Artist(
                            $item['id'],
                            $item['name'],
                            $item['followers']['total'] ?? 0,
                            $item['popularity'] ?? rand(50, 95),   
                            $item['genres'] ?? []
                        );
                    }

                // Créez l'artiste
                $artist = new Artist($artistId, $artistName, $followers, $popularity, $genres);

                // Définir l'URL de l'image
                if (!empty($item['images'])) {
                    try {
                        $imageUrl = $item['images'][0]['url'];
                        $artist->setImageUrl($imageUrl);
                    } catch (\Exception $e) {
                
                        error_log("Erreur lors de la définition de l'URL de l'image : " . $e->getMessage());
                    }
                }

                $results['artists'][] = $artist;
            }
            
            // Traiter les albums
            if (isset($data['albums']['items'])) {
                foreach ($data['albums']['items'] as $item) {
                    $results['albums'][] = new Album(
                        $item['id'],
                        $item['name'],
                        $item['artists'][0]['name'],
                        $item['artists'][0]['id'],
                        $item['release_date'],
                        $item['total_tracks'],
                        isset($item['images'][0]) ? $item['images'][0]['url'] : null
                    );
                }
            }
            
            // Traiter les playlists
            if (isset($data['playlists']['items'])) {
                foreach ($data['playlists']['items'] as $item) {
                    $results['playlists'][] = new Playlist(
                        $item['id'],
                        $item['name'],
                        $item['description'] ?? '',
                        $item['owner']['display_name'],
                        isset($item['images'][0]) ? $item['images'][0]['url'] : null,
                        $item['tracks']['total']
                    );
                }
            }
            
            // Ajouter des points SPOT'VIP pour une recherche
            $this->user_progress->addPoints(1, 'Recherche effectuée');
            
            return view('pages.search', [
                'query' => $query,
                'results' => $results,
                'userProgress' => $this->user_progress,
                'genres' => []
            ]);
            
        } catch (\Exception $e) {

            error_log('Erreur API Spotify (search): ' . $e->getMessage());
            
            $results = $this->getFallbackSearchResults($query);
            
            return view('pages.search', [
                'query' => $query,
                'results' => $results,
                'userProgress' => $this->user_progress,
                'genres' => []
            ]);
        }
    } else {
        // Si aucune requête n'est spécifiée, afficher la page d'exploration avec les genres
        $genres = $this->getMusicGenres();
        
        return view('pages.search', [
            'query' => '',
            'results' => [],
            'userProgress' => $this->user_progress,
            'genres' => $genres
        ]);
    }
}

/**
 * Récupère une liste de genres musicaux
 * 
 * @return array
 */
private function getMusicGenres()
{
    try {
        $response = $this->client->request('GET', 'https://api.spotify.com/v1/browse/categories', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->api_token,
                'Accept' => 'application/json',
            ],
            'query' => [
                'limit' => 40,
                'locale' => 'fr_FR'
            ],
            'verify' => false 
        ]);
        
        $data = json_decode($response->getBody(), true);
        
        $genres = [];
        if (isset($data['categories']['items'])) {
            foreach ($data['categories']['items'] as $category) {
                $genres[] = $category['name'];
            }
        }
        
        return $genres;
        
    } catch (\Exception $e) {
        error_log('Erreur API Spotify (getMusicGenres): ' . $e->getMessage());
        
        // Liste de genres par défaut en cas d'erreur
        return [
            'Pop', 'Hip-Hop', 'Rock', 'Dance / Électro', 'R&B', 'Indie', 
            'Jazz', 'Métal', 'Classique', 'Reggae', 'Soul', 'Funk', 
            'Country', 'Folk', 'Blues', 'Punk', 'Latino', 'Rap français',
            'Chanson française', 'K-pop', 'J-pop', 'Ambient', 'Chill',
            'Workout', 'Gaming', 'Party', 'Focus', 'Sleep', 'Afro'
        ];
    }
}

/**
 * Génère des résultats de recherche de substitution en cas d'erreur API
 * 
 * @param string $query
 * @return array
 */
private function getFallbackSearchResults($query)
{
    $results = [
        'tracks' => [],
        'artists' => [],
        'albums' => [],
        'playlists' => []
    ];
    
    // Générer des titres simulés
    for ($i = 0; $i < 5; $i++) {
        $trackName = "Titre " . ($i + 1) . " (" . $query . ")";
        $artistName = "Artiste " . ($i + 1);
        $albumName = "Album " . ($i + 1);
        $durationMs = rand(180000, 300000);
        
        $results['tracks'][] = new Track(
            'track-' . ($i + 1),
            $trackName,
            $artistName,
            'artist-' . ($i + 1),
            $durationMs,
            null,
            '/img/covers/album' . (($i % 4) + 1) . '.jpg',
            $albumName,
            'album-' . ($i + 1)
        );
    }
    
    // Générer des artistes simulés
    for ($i = 0; $i < 4; $i++) {
        $artist = new Artist(
            'artist-' . ($i + 1),                     
            "Artiste " . ($i + 1) . " (" . $query . ")",
            rand(10000, 1000000),                     
            rand(50, 95),                             
            ['Pop', 'Rock', 'Hip-Hop'] 
        );
        
        $artist->setImageUrl('/img/artist-' . (($i % 4) + 1) . '.jpg');
        
        $results['artists'][] = $artist;
    }
    // Générer des albums simulés
    for ($i = 0; $i < 6; $i++) {
        $results['albums'][] = new Album(
            'album-' . ($i + 1),
            "Album " . ($i + 1) . " (" . $query . ")",
            "Artiste " . ($i % 4 + 1),
            'artist-' . ($i % 4 + 1),
            date('Y-m-d', strtotime('-' . rand(1, 365) . ' days')),
            rand(8, 16),
            '/img/covers/album' . (($i % 4) + 1) . '.jpg'
        );
    }
    
    // Générer des playlists simulées
    for ($i = 0; $i < 4; $i++) {
        $results['playlists'][] = new Playlist(
            'playlist-' . ($i + 1),
            "Playlist " . ($i + 1) . " " . $query,
            "Une playlist avec des titres liés à " . $query,
            "Utilisateur " . ($i + 1),
            '/img/playlist-' . (($i % 8) + 1) . '.png',
            rand(15, 100)
        );
    }
    
    return $results;
}
}