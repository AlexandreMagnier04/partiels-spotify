@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <!-- Section d'accueil personnalisée -->
    <div class="welcome-section">
        <h1 class="greeting-text">Bonjour</h1>
        
        <div class="shortcuts-grid">
            <!-- Raccourcis d'accès rapide -->
            <div class="shortcut-item">
                <div class="shortcut-card">
                    <img src="/img/covers/liked-songs.jpg" alt="Titres likés" class="shortcut-img">
                    <div class="shortcut-info">
                        <h5>Titres likés</h5>
                    </div>
                    <button class="play-btn-overlay"><i class="fas fa-play"></i></button>
                </div>
            </div>
            
            <!-- Morceaux récemment écoutés -->
            @foreach($recentlyPlayed as $track)
                @if($loop->index < 5)
                <div class="shortcut-item">
                    <div class="shortcut-card">
                        <img src="{{ $track->getImageUrl() }}" alt="{{ $track->getName() }}" class="shortcut-img">
                        <div class="shortcut-info">
                            <h5>{{ $track->getName() }}</h5>
                        </div>
                        <button class="play-btn-overlay"><i class="fas fa-play"></i></button>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    
    <!-- Section des playlists mises en avant -->
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Playlists Spotify</h2>
            <a href="{{ route('featuredPlaylists') }}" class="see-all-link">Voir tout</a>
        </div>
        
        <div class="grid">
            @foreach($featuredPlaylists as $playlist)
            <div class="grid-item">
                <div class="card">
                    <div class="card-img-container">
                        <img src="{{ $playlist->getImageUrl() }}" class="card-img" alt="{{ $playlist->getName() }}">
                        <button class="play-btn-overlay"><i class="fas fa-play"></i></button>
                    </div>
                    <h3 class="card-title">{{ $playlist->getName() }}</h3>
                    <p class="card-text">{{ $playlist->getDescription() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    
    <!-- Section de recommandations personnalisées -->
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Créé pour vous</h2>
            <a href="{{ route('recommendations') }}" class="see-all-link">Voir tout</a>
        </div>
        
        <div class="grid">
            @foreach($recommendations as $recommendation)
            <div class="grid-item">
                <div class="card">
                    <div class="card-img-container">
                        <img src="{{ $recommendation->getImageUrl() }}" class="card-img" alt="{{ $recommendation->getName() }}">
                        <button class="play-btn-overlay"><i class="fas fa-play"></i></button>
                    </div>
                    <h3 class="card-title">{{ $recommendation->getName() }}</h3>
                    <p class="card-text">{{ $recommendation->getDescription() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    
    <!-- Section des nouvelles sorties -->
    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Nouvelles sorties</h2>
            <a href="{{ route('newReleases') }}" class="see-all-link">Voir tout</a>
        </div>
        
        <div class="grid">
            @foreach($newReleases as $album)
            <div class="grid-item">
                <div class="card">
                    <div class="card-img-container">
                        <img src="{{ $album->getImageUrl() }}" class="card-img" alt="{{ $album->getName() }}">
                        <button class="play-btn-overlay"><i class="fas fa-play"></i></button>
                    </div>
                    <h3 class="card-title">
                        <a href="{{ route('albumDetails', ['id' => $album->getId()]) }}">{{ $album->getName() }}</a>
                    </h3>
                    <p class="card-text">{{ $album->getArtist() }}</p>
                </div>
            </div>
            @endforeach
            @foreach($newReleases as $album)
            <div class="grid-item">
                <div class="card">
                    <div class="card-img-container">
                        <img src="{{ $album->getImageUrl() }}" class="card-img" alt="{{ $album->getName() }}">
                        <button class="play-btn-overlay"><i class="fas fa-play"></i></button>
                    </div>
                    <h3 class="card-title">
                        <a href="{{ route('albumDetails', ['id' => $album->getId()]) }}">{{ $album->getName() }}</a>
                    </h3>
                    <p class="card-text">{{ $album->getArtist() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Script pour afficher les boutons play au survol
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        const shortcuts = document.querySelectorAll('.shortcut-card');
        
        // Gestion du survol pour les cartes
        cards.forEach(card => {
            const playBtn = card.querySelector('.play-btn-overlay');
            
            card.addEventListener('mouseenter', function() {
                if (playBtn) playBtn.style.opacity = '1';
            });
            
            card.addEventListener('mouseleave', function() {
                if (playBtn) playBtn.style.opacity = '0';
            });
        });
        
        // Gestion du survol pour les raccourcis
        shortcuts.forEach(shortcut => {
            const playBtn = shortcut.querySelector('.play-btn-overlay');
            
            shortcut.addEventListener('mouseenter', function() {
                if (playBtn) playBtn.style.opacity = '1';
            });
            
            shortcut.addEventListener('mouseleave', function() {
                if (playBtn) playBtn.style.opacity = '0';
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
                        const card = this.closest('.card') || this.closest('.shortcut-card');
                        const trackTitle = card.querySelector('.card-title')?.textContent || card.querySelector('h5')?.textContent;
                        const artistInfo = card.querySelector('.card-text')?.textContent || 'Artiste';
                        const coverImg = card.querySelector('img').src;
                        
                        playerTrackName.textContent = trackTitle;
                        playerArtistName.textContent = artistInfo;
                        playerCover.src = coverImg;
                        
                        // Changer l'icône du bouton play principal
                        const mainPlayBtn = document.querySelector('.player-controls .play-btn i');
                        if (mainPlayBtn) {
                            mainPlayBtn.classList.remove('fa-play');
                            mainPlayBtn.classList.add('fa-pause');
                        }
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