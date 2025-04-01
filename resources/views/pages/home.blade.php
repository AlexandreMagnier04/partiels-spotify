@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
{{-- Cette page a été modifiée pour utiliser vos images spécifiques --}}
<div class="main-content">
    <!-- Barre de recherche tout en haut centrée -->
    <div class="top-search-container">
        <div class="search-buttons">
            <a href="{{ route('home') }}" class="home-button">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <form action="{{ route('search') }}" method="GET">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" name="q" placeholder="Que souhaitez-vous écouter ?">
            </div>
        </form>
    </div>
    
    <!-- En-tête avec informations utilisateur -->
    <div class="user-welcome">
        <div class="user-header">
            <div class="user-info">
                <div class="user-avatar">{{ substr($userProgress->getUsername(), 0, 1) }}</div>
                <h1 class="welcome-title">Bonjour {{ $userProgress->getUsername() }}</h1>
            </div>
            <a href="{{ route('profile') }}" class="profile-link">
                <div class="profile-icon">
                    <i class="fas fa-user"></i>
                </div>
            </a>
        </div>
        
        <div class="progress-section-left">
            <h3 class="progress-title">Ma progression</h3>
            <div class="spotvip-progress">
                <div class="spotvip-bar" style="width: {{ $userProgress->getProgressPercentage() }}%;"></div>
            </div>
            <div class="spotvip-stats">
                <span>{{ $userProgress->getCurrentPoints() }} / {{ $userProgress->getMaxPoints() }} SPOINTS</span>
                <span>{{ $userProgress->getSpointsToNextLevel() }} points pour atteindre le niveau {{ $userProgress->getLevel() + 1 }}</span>
            </div>
        </div>
    </div>

    <!-- Première section avec séparation verticale -->
    <div class="first-section">
        <!-- Mes playlists et suggestions de playlists -->
        <div class="section-row">
            <div class="column">
                <h2 class="section-title spotify-green">Mes playlists</h2>
                <div class="card-grid">
                    <div class="content-card">
                        <img src="/img/playlist-1.png" alt="Ma playlist 1" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/playlist-2.png" alt="Ma playlist 2" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/playlist-7.png" alt="Ma playlist 3" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/playlist-3.png" alt="Ma playlist 4" class="card-cover">
                    </div>
                </div>
            </div>
            
            <!-- Séparateur vertical -->
            <div class="vertical-divider"></div>
            
            <div class="column">
                <h2 class="section-title spotify-green">Playlists suggérées</h2>
                <div class="card-grid">
                    <div class="content-card">
                        <img src="/img/playlist-4.png" alt="Playlist suggérée 1" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/playlist-5.png" alt="Playlist suggérée 2" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/playlist-6.png" alt="Playlist suggérée 3" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/playlist-8.png" alt="Playlist suggérée 4" class="card-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- Albums favoris et suggestions d'albums -->
        <div class="section-row">
            <div class="column">
                <h2 class="section-title spotify-green">Albums favoris</h2>
                <div class="card-grid">
                    <div class="content-card">
                        <img src="/img/album-1.png" alt="Album 1" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/album-2.png" alt="Album 2" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/album-8.png" alt="Album 3" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/album-9.png" alt="Album 4" class="card-cover">
                    </div>
                </div>
            </div>
            
            <!-- Séparateur vertical -->
            <div class="vertical-divider"></div>
            
            <div class="column">
                <h2 class="section-title spotify-green">Albums suggérés</h2>
                <div class="card-grid">
                    <div class="content-card">
                        <img src="/img/album-3.png" alt="Album suggéré 1" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/album-4.png" alt="Album suggéré 2" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/album-7.png" alt="Album suggéré 3" class="card-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/album-10.png" alt="Album suggéré 4" class="card-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- Mes artistes et suggestions d'artistes -->
        <div class="section-row">
            <div class="column">
                <h2 class="section-title spotify-green">Mes artistes</h2>
                <div class="card-grid">
                    <div class="content-card">
                        <img src="/img/david-guetta.jpeg" alt="David Guetta" class="card-cover artist-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/AT-_-GAZO.jpg" alt="Gazo" class="card-cover artist-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/gimd.jpg" alt="Artiste 1" class="card-cover artist-cover">
                    </div>
                    <div class="content-card ">
                        <img src="/img/guy2bezbar.jpg" alt="Artiste 2" class="card-cover artist-cover">
                    </div>
                </div>
            </div>
            
            <!-- Séparateur vertical -->
            <div class="vertical-divider"></div>
            
            <div class="column">
                <h2 class="section-title spotify-green">Artistes suggérés</h2>
                <div class="card-grid">
                    <div class="content-card">
                        <img src="/img/iam.avif" alt="Artiste suggéré 1" class="card-cover artist-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/" alt="Artiste suggéré 2" class="card-cover artist-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/" alt="Artiste suggéré 3" class="card-cover artist-cover">
                    </div>
                    <div class="content-card">
                        <img src="/img/" alt="Artiste suggéré 4" class="card-cover artist-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deuxième section sans séparation verticale -->
    <div class="second-section">
        <h2 class="section-title spotify-green full-width-title">Nouveautés</h2>
        <div class="card-grid wide-grid">
            @foreach($suggestedAlbums as $index => $album)
                <div class="content-card">
                    <img src="{{ $album->getImageUrl() }}" alt="{{ $album->getName() }}" class="card-cover">
                    <div class="card-info">
                        <div class="card-title">{{ $album->getName() }}</div>
                        <div class="card-subtitle">{{ $album->getArtist() }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .main-content {
        padding: 20px 30px;
    }
    
    /* Barre de recherche tout en haut centrée */
    .top-search-container {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        position: relative;
        padding: 5px 0;
        justify-content: center;
    }
    
    .search-buttons {
        position: absolute;
        left: 0;
        display: flex;
        align-items: center;
    }
    
    .home-button {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--spotify-dark-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--spotify-white);
        transition: background-color 0.3s;
    }
    
    .home-button:hover {
        background-color: var(--spotify-medium-gray);
        text-decoration: none;
    }
    
    .search-input-wrapper {
        position: relative;
        width: 500px;
        max-width: 90%;
    }
    
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--spotify-off-white);
        font-size: 16px;
    }
    
    .search-input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border-radius: 30px;
        border: none;
        background-color: var(--spotify-medium-gray);
        color: var(--spotify-white);
        font-size: 14px;
    }
    
    .search-input:focus {
        outline: none;
        background-color: var(--spotify-light-gray);
    }
    
    .search-input::placeholder {
        color: var(--spotify-off-white);
    }
    
    /* En-tête avec informations utilisateur */
    .user-welcome {
        margin-bottom: 40px;
    }
    
    .user-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .user-info {
        display: flex;
        align-items: center;
    }
    
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: var(--spotify-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: bold;
        margin-right: 15px;
        color: white;
    }
    
    .profile-link {
        text-decoration: none;
    }
    
    .profile-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: var(--spotify-dark-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: background-color 0.3s;
    }
    
    .profile-icon:hover {
        background-color: var(--spotify-medium-gray);
    }
    
    .welcome-title {
        font-size: 2.5rem;
        font-weight: bold;
    }
    
    /* Barre de progression alignée à gauche */
    .progress-section-left {
        max-width: 500px;
        margin-left: 75px;
        margin-top: 10px;
    }
    
    .progress-title {
        font-size: 16px;
        margin-bottom: 10px;
        color: var(--spotify-off-white);
        text-align: left;
    }
    
    .spotvip-progress {
        height: 8px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }
    
    .spotvip-bar {
        height: 100%;
        background-color: var(--spotify-green);
        border-radius: 4px;
    }
    
    .spotvip-stats {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: var(--spotify-off-white);
    }
    
    /* Layout des sections */
    .first-section, .second-section {
        margin-bottom: 40px;
    }
    
    .section-row {
        display: flex;
        margin-bottom: 40px;
    }
    
    .second-section .section-row {
        margin-bottom: 0;
    }
    
    .column {
        flex: 1;
        padding: 0 20px;
    }
    
    .vertical-divider {
        width: 2px;
        background-color: var(--spotify-green);
        margin: 0 20px;
        height: auto;
    }
    
    .section-title {
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .full-width-title {
        padding: 0 20px;
        margin-bottom: 20px;
    }
    
    .spotify-green {
        color: var(--spotify-green);
    }
    
    /* Grilles de cartes */
   
/* Réduire la taille de toutes les cards */
.card-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px; /* Réduit l'espace entre les cartes */
}

.content-card {
    max-width: 140px; /* Limite la largeur maximale */
    max-height: 180px; /* Limite la hauteur maximale */
    margin: 0 auto; /* Centre si nécessaire */
}

.card-cover {
    width: 100%;
    height: auto;
    max-height: 140px;
    object-fit: cover;
}

/* Pour les artistes (images circulaires) */
.artist-card .card-cover {
    width: 120px;
    height: 120px;
    border-radius: 50%;
}

/* Pour les images de mix */
.mix-cover img {
    width: 50px;
    height: 50px;
    object-fit: cover;
}

/* Pour les images de playlist dans la page profil */
.playlist-cover img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
}

/* Ajuster la grille des nouveautés */
.wide-grid {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
}
    
    /* Responsive */
    @media (max-width: 1024px) {
        .wide-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .section-row {
            flex-direction: column;
        }
        
        .vertical-divider {
            width: auto;
            height: 2px;
            margin: 20px 0;
        }
        
        .wide-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .search-container {
            margin-top: 30px;
        }
        
        .search-input-wrapper {
            max-width: 100%;
        }
    }
    
    @media (max-width: 480px) {
        .card-grid, .wide-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush