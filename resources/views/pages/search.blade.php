@extends('layouts.app')

@section('title', 'Recherche')

@section('content')
    <!-- Barre de recherche -->
    <div class="search-container">
        <form action="{{ route('search') }}" method="GET">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" name="q" placeholder="Que souhaitez-vous écouter ?" value="{{ $query }}">
            </div>
        </form>
    </div>
    
    @if($query)
        <!-- Résultats de recherche -->
        <div class="search-results">
            <!-- Titres -->
            @if(count($results['tracks']) > 0)
                <section class="section">
                    <div class="section-header">
                        <h2 class="section-title">Titres</h2>
                        <a href="#" class="see-all-link">Voir tout</a>
                    </div>
                    
                    <div class="tracks-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Titre</th>
                                    <th>Album</th>
                                    <th><i class="far fa-clock"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['tracks'] as $index => $track)
                                <tr>
                                    <td class="track-number">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="track-title-cell">
                                            <img src="{{ $track->getImageUrl() }}" alt="{{ $track->getName() }}" width="40" height="40">
                                            <div>
                                                <div><a href="{{ route('trackDetails', ['id' => $track->getId()]) }}">{{ $track->getName() }}</a></div>
                                                <div class="text-muted">{{ $track->getArtist() }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><a href="{{ route('albumDetails', ['id' => $track->getAlbumId()]) }}">{{ $track->getAlbumName() }}</a></td>
                                    <td class="track-duration">{{ $track->getFormattedDuration() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
            
            <!-- Artistes -->
            @if(count($results['artists']) > 0)
                <section class="section">
                    <div class="section-header">
                        <h2 class="section-title">Artistes</h2>
                        <a href="#" class="see-all-link">Voir tout</a>
                    </div>
                    
                    <div class="grid">
                        @foreach($results['artists'] as $artist)
                        <div class="grid-item">
                            <div class="card artist-card">
                                <div class="card-img-container">
                                    <img src="{{ $artist->getImageUrl() }}" class="card-img artist-img" alt="{{ $artist->getName() }}">
                                </div>
                                <h3 class="card-title">{{ $artist->getName() }}</h3>
                                <p class="card-text">Artiste • {{ $artist->getFormattedFollowers() }} followers</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            @endif
            
            <!-- Albums -->
            @if(count($results['albums']) > 0)
                <section class="section">
                    <div class="section-header">
                        <h2 class="section-title">Albums</h2>
                        <a href="#" class="see-all-link">Voir tout</a>
                    </div>
                    
                    <div class="grid">
                        @foreach($results['albums'] as $album)
                        <div class="grid-item">
                            <div class="card">
                                <div class="card-img-container">
                                    <img src="{{ $album->getImageUrl() }}" class="card-img" alt="{{ $album->getName() }}">
                                    <button class="play-btn-overlay"><i class="fas fa-play"></i></button>
                                </div>
                                <h3 class="card-title"><a href="{{ route('albumDetails', ['id' => $album->getId()]) }}">{{ $album->getName() }}</a></h3>
                                <p class="card-text">{{ $album->getArtist() }} • {{ $album->getReleaseDate() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            @endif
            
            <!-- Playlists -->
            @if(count($results['playlists']) > 0)
                <section class="section">
                    <div class="section-header">
                        <h2 class="section-title">Playlists</h2>
                        <a href="#" class="see-all-link">Voir tout</a>
                    </div>
                    
                    <div class="grid">
                        @foreach($results['playlists'] as $playlist)
                        <div class="grid-item">
                            <div class="card">
                                <div class="card-img-container">
                                    <img src="{{ $playlist->getImageUrl() }}" class="card-img" alt="{{ $playlist->getName() }}">
                                    <button class="play-btn-overlay"><i class="fas fa-play"></i></button>
                                </div>
                                <h3 class="card-title">{{ $playlist->getName() }}</h3>
                                <p class="card-text">{{ $playlist->getDescription() }}</p>
                                <div class="card-footer">
                                    <span class="text-muted">Par {{ $playlist->getOwner() }} • {{ $playlist->getTracksCount() }} titres</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            @endif
            
            @if(count($results['tracks']) === 0 && count($results['artists']) === 0 && count($results['albums']) === 0 && count($results['playlists']) === 0)
                <div class="no-results">
                    <h3>Aucun résultat trouvé pour "{{ $query }}"</h3>
                    <p class="text-muted">Vérifiez l'orthographe des mots ou essayez d'autres mots-clés.</p>
                </div>
            @endif
        </div>
    @else
        <!-- Explorer les genres -->
        <div class="genres-container">
            <h2 class="section-title">Parcourir tout</h2>
            <div class="genres-grid">
                @foreach($genres as $genre)
                <div class="genre-card animated-item" style="background-color: {{ '#' . substr(md5($genre), 0, 6) }};">
                    <h3 class="genre-title">{{ $genre }}</h3>
                </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation d'entrée pour les cartes de genre
        const genreCards = document.querySelectorAll('.genre-card');
        genreCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s, transform 0.5s';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 + (index * 50));
        });
        
        // Gestion du survol pour les boutons de lecture
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            const playBtn = card.querySelector('.play-btn-overlay');
            
            if (playBtn) {
                card.addEventListener('mouseenter', function() {
                    playBtn.style.opacity = '1';
                });
                
                card.addEventListener('mouseleave', function() {
                    playBtn.style.opacity = '0';
                });
                
                // Gestion du clic sur le bouton play
                playBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('fa-play')) {
                        icon.classList.remove('fa-play');
                        icon.classList.add('fa-pause');
                        
                        // Mettre à jour le lecteur (simulation)
                        const playerTrackName = document.querySelector('.track-name');
                        const playerArtistName = document.querySelector('.track-artist');
                        const playerCover = document.querySelector('.album-thumbnail');
                        
                        if (playerTrackName && playerArtistName && playerCover) {
                            const trackTitle = card.querySelector('.card-title').textContent;
                            const artistInfo = card.querySelector('.card-text').textContent.split('•')[0].trim();
                            const coverImg = card.querySelector('.card-img').src;
                            
                            playerTrackName.textContent = trackTitle;
                            playerArtistName.textContent = artistInfo;
                            playerCover.src = coverImg;
                        }
                    } else {
                        icon.classList.remove('fa-pause');
                        icon.classList.add('fa-play');
                    }
                });
            }
        });
    });
</script>
@endpush