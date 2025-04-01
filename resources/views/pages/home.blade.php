@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="main-content">
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
        
        <div class="progress-section">
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

    <!-- Contenu principal en 2 colonnes -->
    <div class="two-column-layout">
        <!-- Colonne gauche -->
        <div class="column">
            <!-- Mes playlists -->
            <div class="section">
                <h2 class="section-title spotify-green">Mes playlists</h2>
                <div class="card-grid">
                    @foreach($userPlaylists as $index => $playlist)
                        @if($index < 4)
                        <div class="content-card">
                            <img src="{{ $playlist->getImageUrl() }}" alt="{{ $playlist->getName() }}" class="card-cover">
                            <div class="card-info">
                                <div class="card-title">{{ $playlist->getName() }}</div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <!-- Mes albums favoris -->
            <div class="section">
                <h2 class="section-title spotify-green">Albums favoris</h2>
                <div class="card-grid">
                    @foreach($userFavorites as $index => $album)
                        @if($index < 4)
                        <div class="content-card">
                            <img src="{{ $album->getImageUrl() }}" alt="{{ $album->getName() }}" class="card-cover">
                            <div class="card-info">
                                <div class="card-title">{{ $album->getName() }}</div>
                                <div class="card-subtitle">{{ $album->getArtist() }}</div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Séparateur vertical -->
        <div class="vertical-divider"></div>
        
        <!-- Colonne droite (suggestions) -->
        <div class="column">
            <h2 class="column-title spotify-green text-center">Pensé pour moi</h2>
            
            <!-- Playlists suggérées -->
            <div class="section">
                <h3 class="section-subtitle spotify-green">Playlists</h3>
                <div class="card-grid">
                    @foreach($suggestedPlaylists as $index => $playlist)
                        @if($index < 4)
                        <div class="content-card">
                            <img src="{{ $playlist->getImageUrl() }}" alt="{{ $playlist->getName() }}" class="card-cover">
                            <div class="card-info">
                                <div class="card-title">{{ $playlist->getName() }}</div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <!-- Albums suggérés -->
            <div class="section">
                <h3 class="section-subtitle spotify-green">Album</h3>
                <div class="card-grid">
                    @foreach($userFavorites as $index => $album)
                        @if($index < 4)
                        <div class="content-card">
                            <img src="{{ $album->getImageUrl() }}" alt="{{ $album->getName() }}" class="card-cover">
                            <div class="card-info">
                                <div class="card-title">{{ $album->getName() }}</div>
                                <div class="card-subtitle">{{ $album->getArtist() }}</div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- nouveautés -->
            <div class="card-grid">
                @foreach($suggestedAlbums as $index => $album)
                    @if($index < 4)
                    <div class="content-card">
                        <img src="{{ $album->getImageUrl() }}" alt="{{ $album->getName() }}" class="card-cover">
                        <div class="card-info">
                            <div class="card-title">{{ $album->getName() }}</div>
                            <div class="card-subtitle">{{ $album->getArtist() }}</div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .main-content {
        padding: 20px 30px;
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
    
    .progress-section {
        max-width: 500px;
    }
    
    .progress-title {
        font-size: 16px;
        margin-bottom: 10px;
        color: var(--spotify-off-white);
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
    
    /* Layout en deux colonnes */
    .two-column-layout {
        display: flex;
        position: relative;
    }
    
    .column {
        flex: 1;
        padding: 0 20px;
    }
    
    .vertical-divider {
        width: 2px;
        background-color: var(--spotify-green);
        margin: 0 20px;
    }
    
    .column-title {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 20px;
    }
    
    .text-center {
        text-align: center;
    }
    
    .spotify-green {
        color: var(--spotify-green);
    }
    
    .section {
        margin-bottom: 30px;
    }
    
    .section-title {
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .section-subtitle {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    /* Grille de cartes */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .content-card {
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 6px;
        overflow: hidden;
        transition: background-color 0.3s, transform 0.3s;
    }
    
    .content-card:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: scale(1.05);
    }
    
    .card-cover {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
    }
    
    .card-info {
        padding: 10px;
    }
    
    .card-title {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .card-subtitle {
        font-size: 0.9rem;
        color: var(--spotify-off-white);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .two-column-layout {
            flex-direction: column;
        }
        
        .vertical-divider {
            width: auto;
            height: 2px;
            margin: 20px 0;
        }
    }
</style>
@endpush