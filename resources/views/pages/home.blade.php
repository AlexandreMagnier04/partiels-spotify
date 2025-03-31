@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Section d'accueil personnalisée -->
    <div class="welcome-section mb-5">
        <h1 class="greeting-text mb-4">Bonjour</h1>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-3 g-3">
            <!-- Raccourcis d'accès rapide -->
            <div class="col">
                <div class="card bg-spotify-card h-100">
                    <div class="card-horizontal d-flex align-items-center">
                        <img src="/img/covers/liked-songs.jpg" alt="Titres likés" class="shortcut-img" width="80" height="80">
                        <div class="card-body">
                            <h5 class="card-title">Titres likés</h5>
                        </div>
                        <button class="btn btn-success play-btn-overlay rounded-circle me-3 d-none">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Morceaux récemment écoutés -->
            @foreach($recentlyPlayed as $track)
                @if($loop->index < 5)
                <div class="col">
                    <div class="card bg-spotify-card h-100">
                        <div class="card-horizontal d-flex align-items-center">
                            <img src="{{ $track->getImageUrl() }}" alt="{{ $track->getName() }}" class="shortcut-img" width="80" height="80">
                            <div class="card-body">
                                <h5 class="card-title">{{ $track->getName() }}</h5>
                            </div>
                            <button class="btn btn-success play-btn-overlay rounded-circle me-3 d-none">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    
    <!-- Section des playlists mises en avant -->
    <section class="featured-playlists mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title">Playlists Spotify</h2>
            <a href="{{ route('featuredPlaylists') }}" class="text-muted text-decoration-none">VOIR TOUT</a>
        </div>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
            @foreach($featuredPlaylists as $playlist)
            <div class="col">
                <div class="card bg-spotify-card h-100">
                    <div class="card-img-container position-relative">
                        <img src="{{ $playlist->getImageUrl() }}" class="card-img-top" alt="{{ $playlist->getName() }}">
                        <button class="btn btn-success play-btn-overlay rounded-circle position-absolute d-none">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $playlist->getName() }}</h5>
                        <p class="card-text text-muted">{{ $playlist->getDescription() }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    
    <!-- Section de recommandations personnalisées -->
    <section class="recommendations mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title">Créé pour vous</h2>
            <a href="{{ route('recommendations') }}" class="text-muted text-decoration-none">VOIR TOUT</a>
        </div>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
            @foreach($recommendations as $recommendation)
            <div class="col">
                <div class="card bg-spotify-card h-100">
                    <div class="card-img-container position-relative">
                        <img src="{{ $recommendation->getImageUrl() }}" class="card-img-top" alt="{{ $recommendation->getName() }}">
                        <button class="btn btn-success play-btn-overlay rounded-circle position-absolute d-none">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $recommendation->getName() }}</h5>
                        <p class="card-text text-muted">{{ $recommendation->getDescription() }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    
    <!-- Section des nouvelles sorties -->
    <section class="new-releases mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title">Nouvelles sorties</h2>
            <a href="{{ route('newReleases') }}" class="text-muted text-decoration-none">VOIR TOUT</a>
        </div>
        
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
            @foreach($newReleases as $album)
            <div class="col">
                <div class="card bg-spotify-card h-100">
                    <div class="card-img-container position-relative">
                        <img src="{{ $album->getImageUrl() }}" class="card-img-top" alt="{{ $album->getName() }}">
                        <button class="btn btn-success play-btn-overlay rounded-circle position-absolute d-none">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><a href="{{ route('albumDetails', ['id' => $album->getId()]) }}" class="text-light text-decoration-none">{{ $album->getName() }}</a></h5>
                        <p class="card-text text-muted">{{ $album->getArtist() }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    // Script pour afficher les boutons play au survol
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                const playBtn = this.querySelector('.play-btn-overlay');
                if (playBtn) {
                    playBtn.classList.remove('d-none');
                }
            });
            
            card.addEventListener('mouseleave', function() {
                const playBtn = this.querySelector('.play-btn-overlay');
                if (playBtn) {
                    playBtn.classList.add('d-none');
                }
            });
        });
        
        // Définir le message de bienvenue en fonction de l'heure
        const greetingText = document.querySelector('.greeting-text');
        if (greetingText) {
            const hour = new Date().getHours();
            let greeting = 'Bonjour';
            
            if (hour >= 18) {
                greeting = 'Bonsoir';
            } else if (hour >= 12) {
                greeting = 'Bon après-midi';
            }
            
            greetingText.textContent = greeting;
        }
        
        // Simuler le clic sur les boutons de lecture
        const playButtons = document.querySelectorAll('.play-btn-overlay');
        playButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Simuler la lecture
                const icon = this.querySelector('i');
                if (icon.classList.contains('fa-play')) {
                    icon.classList.remove('fa-play');
                    icon.classList.add('fa-pause');
                    
                    // Simuler une mise à jour du lecteur principal
                    const playerTrackName = document.querySelector('.track-name');
                    const playerArtistName = document.querySelector('.track-artist');
                    const playerCover = document.querySelector('.album-thumbnail');
                    
                    if (playerTrackName && playerArtistName && playerCover) {
                        const card = this.closest('.card');
                        const trackTitle = card.querySelector('.card-title').textContent;
                        const artistInfo = card.querySelector('.card-text')?.textContent || 'Artiste';
                        const coverImg = card.querySelector('img').src;
                        
                        playerTrackName.textContent = trackTitle;
                        playerArtistName.textContent = artistInfo;
                        playerCover.src = coverImg;
                    }
                } else {
                    icon.classList.remove('fa-pause');
                    icon.classList.add('fa-play');
                }
            });
        });
    });
</script>
@endpush