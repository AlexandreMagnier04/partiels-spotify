<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Playlist;
use App\Models\TrackDetails;
use Carbon\Carbon;


class SpotifyController extends Controller
{
    private $client;
    private $api_token;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        // Dans un projet réel, ce token serait récupéré via l'autorisation OAuth
        // et stocké de manière sécurisée, pas en dur dans le code
        $this->api_token = 'VOTRE_TOKEN_SPOTIFY_API';
    }

    /**
     * Affiche la page d'accueil avec les nouveautés et recommandations
     */
    public function index()
    {
        // Récupérer les nouveautés
        $newReleases = $this->getNewReleases();
        
        // Récupérer les playlists mises en avant
        $featuredPlaylists = $this->getFeaturedPlaylists();
        
        // Dans un cas réel, nous utiliserions le token de l'utilisateur pour accéder à ses données
        // Pour cette démo, nous utilisons des données simulées pour les éléments personnalisés
        $recentlyPlayed = $this->simulateRecentlyPlayed();
        $recommendations = $this->simulateRecommendations();

        return view('pages.home', [
            'newReleases' => $newReleases,
            'featuredPlaylists' => $featuredPlaylists,
            'recentlyPlayed' => $recentlyPlayed,
            'recommendations' => $recommendations
        ]);
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
            // En cas d'erreur, retourner un tableau vide et loguer l'erreur
            // Dans un vrai projet, gérer cette erreur de manière plus élégante
            error_log('Erreur API Spotify (getNewReleases): ' . $e->getMessage());
            return $this->simulateNewReleases();
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
            // En cas d'erreur, retourner un tableau vide et loguer l'erreur
            error_log('Erreur API Spotify (getFeaturedPlaylists): ' . $e->getMessage());
            return $this->simulateFeaturedPlaylists();
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
            
            return view('pages.track-details', [
                'trackDetails' => $trackDetails,
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur, simuler des données
            error_log('Erreur API Spotify (trackDetails): ' . $e->getMessage());
            return view('pages.track-details', [
                'trackDetails' => $this->simulateTrackDetails($id),
            ]);
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
            
            return view('pages.album-details', [
                'albumDetails' => $albumDetails,
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur, simuler des données
            error_log('Erreur API Spotify (albumDetails): ' . $e->getMessage());
            return view('pages.album-details', [
                'albumDetails' => $this->simulateAlbumDetails($id),
            ]);
        }
    }

    /**
     * Recherche dans Spotify
     */
    public function search(string $query = null)
    {
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
                // En cas d'erreur, simuler des résultats
                error_log('Erreur API Spotify (search): ' . $e->getMessage());
                $results = $this->simulateSearchResults($query);
            }
        }
        
        return view('pages.search', [
            'query' => $query,
            'results' => $results,
            'genres' => $this->getGenresList()
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
     * Récupération des genres (simulée car l'API renvoie une liste limitée)
     */
    private function getGenresList()
    {
        return [
            'Pop', 'Rock', 'Hip-Hop', 'R&B', 'Electronic', 'Dance', 
            'Jazz', 'Classical', 'Country', 'Folk', 'Metal', 'Punk', 
            'Indie', 'Alternative', 'Reggae', 'Blues', 'Soul'
        ];
    }

    /**
     * Afficher la bibliothèque (simulée)
     */
    public function library()
    {
        // Dans un projet réel, récupérer les données depuis l'API
        $playlists = $this->simulateUserPlaylists();
        $savedAlbums = $this->simulateSavedAlbums();
        $artists = $this->simulateFollowedArtists();
        
        return view('pages.library', [
            'playlists' => $playlists,
            'savedAlbums' => $savedAlbums,
            'artists' => $artists
        ]);
    }

    /**
     * Méthodes de simulation de données en cas d'erreur API ou pour la démo
     */
    private function simulateNewReleases()
    {
        return [
            new Album('album1', 'New Album Title 1', 'Popular Artist 1', 'artist1', '31 mars 2024', 12, '/img/covers/new-release-1.jpg'),
            new Album('album2', 'New Album Title 2', 'Popular Artist 2', 'artist2', '25 mars 2024', 10, '/img/covers/new-release-2.jpg'),
            new Album('album3', 'New Album Title 3', 'Popular Artist 3', 'artist3', '20 mars 2024', 15, '/img/covers/new-release-3.jpg'),
            new Album('album4', 'New Album Title 4', 'Popular Artist 4', 'artist4', '15 mars 2024', 8, '/img/covers/new-release-4.jpg'),
        ];
    }

    private function simulateFeaturedPlaylists()
    {
        return [
            new Playlist('playlist1', 'Today\'s Top Hits', 'The most played tracks right now', 'Spotify', '/img/covers/todays-top-hits.jpg', 50),
            new Playlist('playlist2', 'RapCaviar', 'Hip hopheavyweight playlist', 'Spotify', '/img/covers/rapcaviar.jpg', 50),
            new Playlist('playlist3', 'Rock Classics', 'Rock legends & epic songs', 'Spotify', '/img/covers/rock-classics.jpg', 75),
            new Playlist('playlist4', 'Chill Hits', 'Kick back with the chillest tracks', 'Spotify', '/img/covers/chill-hits.jpg', 100),
            new Playlist('playlist5', 'Dance Party', 'Move to the rhythm', 'Spotify', '/img/covers/dance-party.jpg', 80),
        ];
    }

    private function simulateRecentlyPlayed()
    {
        return [
            new Track('track1', 'Blinding Lights', 'The Weeknd', 'artist1', 200000, 'preview-url', '/img/covers/after-hours.jpg', 'After Hours', 'album1'),
            new Track('track2', 'Dance Monkey', 'Tones and I', 'artist2', 209000, 'preview-url', '/img/covers/kids-coming.jpg', 'The Kids Are Coming', 'album2'),
            new Track('track3', 'Watermelon Sugar', 'Harry Styles', 'artist3', 174000, 'preview-url', '/img/covers/fine-line.jpg', 'Fine Line', 'album3'),
            new Track('track4', 'Don\'t Start Now', 'Dua Lipa', 'artist4', 183000, 'preview-url', '/img/covers/future-nostalgia.jpg', 'Future Nostalgia', 'album4'),
            new Track('track5', 'Circles', 'Post Malone', 'artist5', 215000, 'preview-url', '/img/covers/hollywood-bleeding.jpg', 'Hollywood\'s Bleeding', 'album5'),
        ];
    }

    private function simulateRecommendations()
    {
        return [
            new Playlist('playlist6', 'Discover Weekly', 'Your weekly mixtape of fresh music', 'Spotify', '/img/covers/discover-weekly.jpg', 30),
            new Playlist('playlist7', 'Release Radar', 'New releases from artists you follow', 'Spotify', '/img/covers/release-radar.jpg', 30),
            new Playlist('playlist8', 'Daily Mix 1', 'The Weeknd, Dua Lipa, Taylor Swift and more', 'Spotify', '/img/covers/daily-mix-1.jpg', 50),
            new Playlist('playlist9', 'Daily Mix 2', 'Drake, Travis Scott, Kanye West and more', 'Spotify', '/img/covers/daily-mix-2.jpg', 50),
        ];
    }

    private function simulateTrackDetails($id)
    {
        return new TrackDetails(
            $id,
            'Track Title for ID ' . $id,
            ['Main Artist', 'Featured Artist'],
            'Album Name',
            'album-id',
            '15 janvier 2024',
            210000, // 3:30 en millisecondes
            75, // popularité sur 100
            0.8, // danceability
            0.7, // energy
            120, // tempo
            5, // key (F#)
            1, // mode (majeur)
            'preview-url',
            '/img/covers/track-cover.jpg'
        );
    }

    private function simulateAlbumDetails($id)
    {
        $tracks = [
            new Track('track-' . $id . '-1', 'Track 1', 'Artist Name', 'artist-id', 204000, 'preview-url'),
            new Track('track-' . $id . '-2', 'Track 2', 'Artist Name', 'artist-id', 165000, 'preview-url'),
            new Track('track-' . $id . '-3', 'Track 3', 'Artist Name', 'artist-id', 252000, 'preview-url'),
        ];
        
        return new Album(
            $id,
            'Album Title for ID ' . $id,
            'Album Artist',
            'artist-id',
            '10 mars 2024',
            count($tracks),
            '/img/covers/album-' . $id . '.jpg',
            $tracks,
            82,
            ['Pop', 'R&B'],
            'Record Label',
            '© 2024 Record Label'
        );
    }

    private function simulateUserPlaylists()
    {
        return [
            new Playlist('user-playlist1', 'My Favorite Songs', 'All my favorite tracks', 'Current User', '/img/covers/my-playlist-1.jpg', 124),
            new Playlist('user-playlist2', 'Workout Mix', 'Songs to keep me motivated', 'Current User', '/img/covers/my-playlist-2.jpg', 45),
            new Playlist('user-playlist3', 'Chill Vibes', 'For relaxing times', 'Current User', '/img/covers/my-playlist-3.jpg', 87),
        ];
    }

    private function simulateSavedAlbums()
    {
        return [
            new Album('saved-album1', 'Album Name 1', 'Artist 1', 'artist1', '10 janvier 2023', 12, '/img/covers/saved-album-1.jpg'),
            new Album('saved-album2', 'Album Name 2', 'Artist 2', 'artist2', '15 décembre 2022', 10, '/img/covers/saved-album-2.jpg'),
        ];
    }

    private function simulateFollowedArtists()
    {
        return [
            new Artist('artist1', 'Artist Name 1', 8500000, 90, ['Pop', 'R&B'], '/img/artists/artist-1.jpg'),
            new Artist('artist2', 'Artist Name 2', 4200000, 85, ['Rock', 'Alternative'], '/img/artists/artist-2.jpg'),
            new Artist('artist3', 'Artist Name 3', 2700000, 78, ['Hip-Hop', 'Rap'], '/img/artists/artist-3.jpg'),
        ];
    }

    private function simulateSearchResults($query)
    {
        return [
            'tracks' => [
                new Track('track-search-1', 'Track with ' . $query, 'Artist Name', 'artist1', 195000, 'preview-url', '/img/covers/search-result-1.jpg', 'Album Name', 'album1'),
                new Track('track-search-2', 'Another ' . $query . ' Song', 'Different Artist', 'artist2', 260000, 'preview-url', '/img/covers/search-result-2.jpg', 'Another Album', 'album2'),
            ],
            'artists' => [
                new Artist('artist-search-1', $query . ' Artist', 5200000, 85, ['Pop', 'R&B'], '/img/artists/search-artist-1.jpg'),
            ],
            'albums' => [
                new Album('album-search-1', 'The ' . $query . ' Album', 'Album Artist', 'artist3', '2023', 12, '/img/covers/search-album-1.jpg'),
            ],
            'playlists' => [
                new Playlist('playlist-search-1', 'Best of ' . $query, 'Collection of best ' . $query . ' songs', 'Playlist Creator', '/img/covers/search-playlist-1.jpg', 50),
            ],
        ];
    }
}