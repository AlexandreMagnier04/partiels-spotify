@extends('layouts.app')

@section('title', $albumDetails->getName())

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header avec image et info album -->
    <div class="album-header mb-5">
        <div class="row align-items-end">
            <div class="col-md-3 mb-3 mb-md-0">
                <img src="{{ $albumDetails->getImageUrl() }}" alt="{{ $albumDetails->getName() }}" class="img-fluid rounded shadow album-cover">
            </div>
            <div class="col-md-9">
                <div class="album-info">
                    <h6 class="text-uppercase text-muted small">ALBUM</h6>
                    <h1 class="display-4 fw-bold mb-2">{{ $albumDetails->getName() }}</h1>
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $albumDetails->getImageUrl() }}" alt="{{ $albumDetails->getArtist() }}" class="rounded-circle me-2" width="30" height="30">
                        <a href="#" class="fw-bold link-light text-decoration-none">{{ $albumDetails->getArtist() }}</a>
                        <span class="mx-2">•</span>
                        <span>{{ $albumDetails->getReleaseDate() }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $albumDetails->getTotalTracks() }} titres</span>
                        @if($albumDetails->getFormattedDuration() !== 'Unknown')
                        <span class="mx-2">•</span>
                        <span>{{ $albumDetails->getFormattedDuration() }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="album-actions mb-4">
        <div class="d-flex align-items-center">
            <button class="btn btn-success btn-lg me-3 play-all">
                <i class="fas fa-play"></i>
            </button>
            <button class="btn btn-outline-light me-2">
                <i class="far fa-heart"></i>
            </button>
            <button class="btn btn-outline-light me-2">
                <i class="fas fa-download"></i>
            </button>
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" id="albumOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="albumOptionsDropdown">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-share-alt me-2"></i> Partager</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-link me-2"></i> Copier le lien</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-exclamation-circle me-2"></i> Signaler</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Liste des titres -->
    <div class="album-tracks mb-5">
        <div class="table-responsive">
            <table class="table table-dark table-hover track-list">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col" class="text-center">Popularité</th>
                        <th scope="col" class="text-center"><i class="far fa-clock"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @if($albumDetails->getTracks())
                        @foreach($albumDetails->getTracks() as $index => $track)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('trackDetails', ['id' => $track->getId()]) }}" class="text-light text-decoration-none track-title">{{ $track->getName() }}</a>
                            </td>
                            <td class="text-center">
                                <div class="popularity-bar">
                                    @php
                                        $popularity = mt_rand(60, 90);
                                    @endphp
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-light" role="progressbar" style="width: {{ $popularity }}%" aria-valuenow="{{ $popularity }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $track->getFormattedDuration() }}</td>
                        </tr>
                        @endforeach
                    @else
                        @for($i = 1; $i <= $albumDetails->getTotalTracks(); $i++)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>Titre {{ $i }}</td>
                            <td class="text-center">
                                <div class="popularity-bar">
                                    @php
                                        $popularity = mt_rand(60, 90);
                                    @endphp
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-light" role="progressbar" style="width: {{ $popularity }}%" aria-valuenow="{{ $popularity }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">3:{{ str_pad(mt_rand(10, 59), 2, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Informations complémentaires -->
    <div class="album-details mb-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-spotify-card">
                    <div class="card-header">
                        <h2>À propos de l'album</h2>
                    </div>
                    <div class="card-body">
                        @if(count($albumDetails->getGenres()) > 0)
                            <div class="mb-3">
                                <h5>Genres</h5>
                                <div class="d-flex flex-wrap">
                                    @foreach($albumDetails->getGenres() as $genre)
                                    <span class="badge bg-secondary m-1 p-2">{{ $genre }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <h5>Popularité</h5>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $albumDetails->getPopularity() ?? 75 }}%"></div>
                            </div>
                            <p class="text-muted small">Score de popularité sur Spotify</p>
                        </div>
                        
                        @if($albumDetails->getLabel())
                            <div class="mb-3">
                                <h5>Label</h5>
                                <p>{{ $albumDetails->getLabel() }}</p>
                            </div>
                        @endif
                        
                        @if($albumDetails->getCopyright())
                            <div>
                                <h5>Copyright</h5>
                                <p class="text-muted small">{{ $albumDetails->getCopyright() }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <div class="card bg-spotify-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2>Artiste</h2>
                        <a href="#" class="text-muted">VOIR PLUS</a>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $albumDetails->getImageUrl() }}" alt="{{ $albumDetails->getArtist() }}" class="rounded-circle me-3" width="80" height="80">
                            <div>
                                <h4>{{ $albumDetails->getArtist() }}</h4>
                                <p class="text-muted">{{ number_format(mt_rand(1000000, 50000000)) }} auditeurs mensuels</p>
                            </div>
                        </div>
                        <button class="btn btn-outline-light">SUIVRE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Albums similaires -->
    <div class="similar-albums mb-4">
        <h2 class="mb-3">Plus par {{ $albumDetails->getArtist() }}</h2>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
            @for($i = 1; $i <= 5; $i++)
            <div class="col">
                <div class="card bg-spotify-card h-100">
                    <div class="card-img-container position-relative">
                        <img src="{{ $albumDetails->getImageUrl() }}" class="card-img-top" alt="Album similaire {{ $i }}">
                        <button class="btn btn-success play-btn-overlay rounded-circle position-absolute d-none">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Album {{ $i }} de {{ $albumDetails->getArtist() }}</h5>
                        <p class="card-text text-muted">{{ mt_rand(2018, 2024) }}</p>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
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
        
        // Gestion du bouton de lecture de l'album
        const playAllButton = document.querySelector('.play-all');
        if (playAllButton) {
            playAllButton.addEventListener('click', function() {
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
        
        // Animer les barres de popularité au chargement
        const popularityBars = document.querySelectorAll('.popularity-bar .progress-bar');
        popularityBars.forEach(bar => {
            const targetWidth = bar.style.width;
            bar.style.width = '0%';
            
            setTimeout(() => {
                bar.style.transition = 'width 1s';
                bar.style.width = targetWidth;
            }, 200);
        });
    });
</script>
@endpush