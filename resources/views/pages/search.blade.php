@extends('layouts.app')

@section('title', 'Recherche')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Barre de recherche -->
    <div class="search-container mb-4">
        <form action="{{ route('search') }}" method="GET">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" name="q" placeholder="Que souhaitez-vous écouter ?" value="{{ $query }}">
            </div>
        </form>
    </div>
    
    @if($query)
        <!-- Résultats de recherche -->
        <div class="search-results">
            <!-- Titres -->
            @if(count($results['tracks']) > 0)
                <section class="tracks-section mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="section-title">Titres</h2>
                        <a href="#" class="text-muted text-decoration-none">VOIR TOUT</a>
                    </div>
                    
                    <div class="tracks-table">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Titre</th>
                                    <th scope="col">Album</th>
                                    <th scope="col" class="text-center"><i class="far fa-clock"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['tracks'] as $index => $track)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $track->getImageUrl() }}" alt="{{ $track->getName() }}" width="40" height="40" class="me-3">
                                            <div>
                                                <div><a href="{{ route('trackDetails', ['id' => $track->getId()]) }}" class="text-light text-decoration-none">{{ $track->getName() }}</a></div>
                                                <div class="text-muted">{{ $track->getArtist() }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><a href="{{ route('albumDetails', ['id' => $track->getAlbumId()]) }}" class="text-light text-decoration-none">{{ $track->getAlbumName() }}</a></td>
                                    <td class="text-center">{{ $track->getFormattedDuration() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
            
            <!-- Artistes -->
            @if(count($results['artists']) > 0)
                <section class="artists-section mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="section-title">Artistes</h2>
                        <a href="#" class="text-muted text-decoration-none">VOIR TOUT</a>
                    </div>
                    
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                        @foreach($results['artists'] as $artist)
                        <div class="col">
                            <div class="card bg-spotify-card h-100 text-center">
                                <img src="{{ $artist->getImageUrl() }}" class="card-img-top rounded-circle mx-auto mt-3" alt="{{ $artist->getName() }}" style="width: 150px; height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $artist->getName() }}</h5>
                                    <p class="card-text text-muted">Artiste • {{ $artist->getFormattedFollowers() }} followers</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            @endif
            
            <!-- Albums -->
            @if(count($results['albums']) > 0)
                <section class="albums-section mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="section-title">Albums</h2>
                        <a href="#" class="text-muted text-decoration-none">VOIR TOUT</a>
                    </div>
                    
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                        @foreach($results['albums'] as $album)
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
                                    <p class="card-text text-muted">{{ $album->getArtist() }} • {{ $album->getReleaseDate() }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            @endif
            
            <!-- Playlists -->
            @if(count($results['playlists']) > 0)
                <section class="playlists-section mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="section-title">Playlists</h2>
                        <a href="#" class="text-muted text-decoration-none">VOIR TOUT</a>
                    </div>
                    
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                        @foreach($results['playlists'] as $playlist)
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
                                <div class="card-footer bg-transparent border-0">
                                    <small class="text-muted">Par {{ $playlist->getOwner() }} • {{ $playlist->getTracksCount() }} titres</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            @endif
            
            @if(count($results['tracks']) === 0 && count($results['artists']) === 0 && count($results['albums']) === 0 && count($results['playlists']) === 0)
                <div class="no-results text-center py-5">
                    <h3>Aucun résultat trouvé pour "{{ $query }}"</h3>
                    <p class="text-muted">Vérifiez l'orthographe des mots ou essayez d'autres mots-clés.</p>
                </div>
            @endif
        </div>
    @else
        <!-- Explorer les genres -->
        <div class="genres-container">
            <h2 class="section-title mb-4">Parcourir tout</h2>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                @foreach($genres as $genre)
                <div class="col">
                    <div class="card genre-card" style="background-color: {{ '#' . substr(md5($genre), 0, 6) }};">
                        <div class="card-body">
                            <h3 class="genre-title">{{ $genre }}</h3>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif
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
    });
</script>
@endpush