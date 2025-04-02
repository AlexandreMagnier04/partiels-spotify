@extends('layouts.app')

@section('title', 'Recherche')

@section('content')
<div class="search-content">
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
                                    <th style="text-align: end"><i class="far fa-clock"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['tracks'] as $index => $track)
                                <tr>
                                    <td class="track-number">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="track-title-cell">
                                            <img src="{{ $track->getImageUrl() }}" alt="{{ $track->getName() }}" width="40" height="40">
                                            <button class="play-btn-overlay search">
                                                <i class="fas fa-play"></i>
                                            </button>
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
            
            <!-- Dernières sorties -->
            @if(count($results['artists']) > 0)
                <section class="section">
                    <div class="section-header">
                        <h2 class="section-title">Dernières sorties / Single</h2>
                        <a href="#" class="see-all-link">Voir tout</a>
                    </div>
                    
                    <div class="grid">
                        @foreach($results['artists'] as $artist)
                        <div class="grid-item">
                            <div class="card artist-card">
                                <div class="card-img-container">
                                    <img src="{{ !empty($item['images']) ? $item['images'][0]['url'] : '/img/default-artist.jpg' }}" class="card-img artist-img" alt="{{ $artist->getName() }}">
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
</div>
@endsection

@push('styles')
<style>

    .section {
        margin-bottom: 40px;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--spotify-white);
    }
    
    .see-all-link {
        color: var(--spotify-off-white);
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .see-all-link:hover {
        color: var(--spotify-white);
        text-decoration: none;
    }
    
    /* Table des titres */
    .tracks-table {
        margin-bottom: 30px;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table th {
        text-align: left;
        padding: 10px;
        color: var(--spotify-off-white);
        font-size: 0.9rem;
        font-weight: 400;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .table td {
        padding: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 0.95rem;
    }
    
    .table tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    .track-number {
        color: var(--spotify-off-white);
        width: 40px;
        text-align: center;
    }
    
    .track-title-cell {
        display: flex;
        align-items: center;
        position: relative;
    }
    
    .track-title-cell img {
        margin-right: 10px;
        border-radius: 4px;
    }
    
    .text-muted {
        color: var(--spotify-off-white);
        font-size: 0.85rem;
    }
    
    .track-duration {
        text-align: right;
        color: var(--spotify-off-white);
    }
    
    /* Grille pour les cards */
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 24px;
    }
    
    .grid-item {
        transition: transform 0.3s;
    }
    
    .card {
        background-color: var(--spotify-light-gray);
        border-radius: 8px;
        padding: 16px;
        transition: background-color 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .card:hover {
        background-color: var(--spotify-medium-gray);
    }
    
    .card-img-container {
        position: relative;
        margin-bottom: 16px;
    }
    
    .card-img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .artist-img {
        border-radius: 50%;
    }
    
    .play-btn-overlay {
        position: absolute;
        bottom: 8px;
        left: 8px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--spotify-green);
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        color: white;
        opacity: 0;
        transition: opacity 0.3s, transform 0.3s;
        transform: translateY(8px);
        cursor: pointer;
    }

    .play-btn-overlay.search{
        left: 0px;
        bottom: 10px;
        width: 30px;
        height: 30px; 
    }
    
    .card:hover .play-btn-overlay,
    tr:hover .play-btn-overlay.search {
        opacity: 1;
        transform: translateY(0);
    }
    
    .card-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .card-text {
        color: var(--spotify-off-white);
        font-size: 0.9rem;
        margin-bottom: 6px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .card-footer {
        margin-top: auto;
        padding-top: 8px;
    }
    
    /* Message pas de résultats */
    .no-results {
        text-align: center;
        padding: 60px 0;
    }
    
    .no-results h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    /* Grille des genres */
    .genres-container {
        padding: 20px 0;
    }
    
    .genres-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }
    
    .genre-card {
        height: 180px;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        align-items: flex-end;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.3s;
    }
    
    .genre-card:hover {
        transform: scale(1.02);
    }
    
    .genre-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 50%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        z-index: 1;
    }
    
    .genre-title {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        position: relative;
        z-index: 2;
    }
    
    /* Animation pour les cartes */
    .animated-item {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeIn 0.5s ease forwards;
    }
    
    @keyframes fadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        }
    }
    
    @media (max-width: 768px) {
        .section-title {
            font-size: 1.2rem;
        }
        
        .grid {
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 16px;
        }
        
        .genres-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }
    
    @media (max-width: 576px) {
        .grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .genres-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .card {
            padding: 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation d'entrée pour les cartes de genre
        const genreCards = document.querySelectorAll('.genre-card');
        genreCards.forEach((card, index) => {
            card.style.animationDelay = index * 50 + 'ms';
        });
        
        // Gestion du survol pour les boutons de lecture
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            const playBtn = card.querySelector('.play-btn-overlay');
            
            if (playBtn) {
                // Gestion du clic sur le bouton play
                playBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('fa-play')) {
                        icon.classList.remove('fa-play');
                        icon.classList.add('fa-pause');
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