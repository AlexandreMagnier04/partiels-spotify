@extends('layouts.app')

@section('title', $trackDetails->getName())

@section('content')
    <div class="track-details-section">
        <!-- Image et informations de base -->
        <div class="track-image-container">
            <img src="{{ $trackDetails->getImageUrl() }}" alt="{{ $trackDetails->getName() }}" class="track-image">
            
            <div class="track-basic-info">
                <h1 class="track-title">{{ $trackDetails->getName() }}</h1>
                <p class="track-artist">
                    Par <strong>{{ $trackDetails->getArtistsString() }}</strong>
                </p>
                <p class="track-album">
                    Album : <a href="{{ route('albumDetails', ['id' => $trackDetails->getAlbumId()]) }}">{{ $trackDetails->getAlbumName() }}</a>
                </p>
                <p class="track-release">
                    Sortie le {{ $trackDetails->getReleaseDate() }}
                </p>
                
                <div class="track-actions">
                    <button class="btn-success play-track" data-preview="{{ $trackDetails->getPreviewUrl() }}">
                        <i class="fas fa-play"></i> Écouter
                    </button>
                    <button class="btn-outline">
                        <i class="far fa-heart"></i>
                    </button>
                    <button class="btn-outline">
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="track-dropdown">
                        <button class="btn-outline dropdown-toggle">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#"><i class="fas fa-share-alt"></i> Partager</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-link"></i> Copier le lien</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"><i class="fas fa-exclamation-circle"></i> Signaler</a>
                        </div>
                    </div>
                </div>
                
                <div class="track-stats">
                    <div class="stat-item">
                        <i class="fas fa-chart-line"></i> Popularité 
                        <span class="badge">{{ $trackDetails->getPopularity() }}/100</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-clock"></i> Durée 
                        <span>{{ $trackDetails->getFormattedDuration() }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Caractéristiques audio et visualisations -->
        <div class="track-info-container">
            <div class="audio-player-section">
                <h2>Extrait audio</h2>
                @if($trackDetails->getPreviewUrl())
                <audio id="audio-player" controls>
                    <source src="{{ $trackDetails->getPreviewUrl() }}" type="audio/mpeg">
                    Votre navigateur ne supporte pas l'élément audio.
                </audio>
                <p class="preview-note">Extrait de 30 secondes</p>
                @else
                <p class="no-preview">Aucun extrait disponible pour ce titre</p>
                @endif
            </div>
            
            <div class="audio-features-section">
                <h2>Caractéristiques audio</h2>
                <div class="audio-features">
                    <!-- Danceability -->
                    <div class="feature-item">
                        <div class="feature-value">{{ number_format($trackDetails->getDanceability() * 100) }}%</div>
                        <div class="feature-label">Danceability</div>
                        <div class="feature-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $trackDetails->getDanceability() * 100 }}%; background-color: #1DB954;"></div>
                            </div>
                        </div>
                        <div class="feature-description">Mesure à quel point le morceau est adapté à la danse.</div>
                    </div>
                    
                    <!-- Energy -->
                    <div class="feature-item">
                        <div class="feature-value">{{ number_format($trackDetails->getEnergy() * 100) }}%</div>
                        <div class="feature-label">Energy</div>
                        <div class="feature-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $trackDetails->getEnergy() * 100 }}%; background-color: #FF5722;"></div>
                            </div>
                        </div>
                        <div class="feature-description">Représente l'intensité et l'activité perçues.</div>
                    </div>
                    
                    <!-- Tempo -->
                    <div class="feature-item">
                        <div class="feature-value">{{ round($trackDetails->getTempo()) }}</div>
                        <div class="feature-label">Tempo (BPM)</div>
                        <div class="feature-description">Vitesse ou rythme estimé en battements par minute.</div>
                    </div>
                    
                    <!-- Key & Mode -->
                    <div class="feature-item">
                        <div class="feature-value">{{ $trackDetails->getKeyName() }}</div>
                        <div class="feature-label">Tonalité <span class="badge">{{ $trackDetails->getModeName() }}</span></div>
                        <div class="feature-description">Tonalité musicale du morceau.</div>
                    </div>
                </div>
            </div>
            
            <!-- Recommandations -->
            <div class="similar-tracks-section">
                <h2>Titres similaires</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="track-number">#</th>
                            <th>Titre</th>
                            <th>Album</th>
                            <th class="track-duration"><i class="far fa-clock"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 1; $i <= 5; $i++)
                        <tr>
                            <td class="track-number">{{ $i }}</td>
                            <td>
                                <div class="track-title-cell">
                                    <img src="{{ $trackDetails->getImageUrl() }}" alt="Similar Track {{ $i }}" width="40" height="40">
                                    <div>
                                        <div>Similar Track {{ $i }}</div>
                                        <div class="text-muted">{{ $trackDetails->getArtistsString() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $trackDetails->getAlbumName() }}</td>
                            <td class="track-duration">3:{{ 10 + $i * 5 }}</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lecteur audio
        const audioPlayer = document.getElementById('audio-player');
        const playButton = document.querySelector('.play-track');
        
        if (playButton && audioPlayer) {
            playButton.addEventListener('click', function() {
                if (audioPlayer.paused) {
                    audioPlayer.play();
                    this.innerHTML = '<i class="fas fa-pause"></i> Pause';
                } else {
                    audioPlayer.pause();
                    this.innerHTML = '<i class="fas fa-play"></i> Écouter';
                }
            });
            
            audioPlayer.addEventListener('ended', function() {
                playButton.innerHTML = '<i class="fas fa-play"></i> Écouter';
            });
        }
        
        // Dropdown menu
        const dropdownToggle = document.querySelector('.dropdown-toggle');
        const dropdownMenu = document.querySelector('.dropdown-menu');
        
        if (dropdownToggle && dropdownMenu) {
            dropdownToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            });
            
            document.addEventListener('click', function(e) {
                if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
    .track-details-section {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
    }
    
    .track-image-container {
        flex: 0 0 300px;
    }
    
    .track-image {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 60px rgba(0, 0, 0, 0.5);
        margin-bottom: 24px;
    }
    
    .track-info-container {
        flex: 1;
        min-width: 300px;
    }
    
    .track-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .track-artist, .track-album, .track-release {
        margin-bottom: 8px;
    }
    
    .track-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 24px 0;
    }
    
    .track-stats {
        margin-top: 24px;
        display: flex;
        gap: 24px;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .badge {
        background-color: var(--spotify-green);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
    }
    
    /* Audio player */
    .audio-player-section {
        margin-bottom: 40px;
    }
    
    #audio-player {
        width: 100%;
        margin-top: 16px;
        background-color: var(--spotify-dark-gray);
        border-radius: 8px;
    }
    
    .preview-note {
        text-align: center;
        color: var(--spotify-off-white);
        margin-top: 8px;
        font-size: 0.9rem;
    }
    
    .no-preview {
        color: var(--spotify-off-white);
        text-align: center;
        padding: 24px;
        background-color: var(--spotify-dark-gray);
        border-radius: 8px;
    }
    
    /* Audio features */
    .audio-features-section {
        margin-bottom: 40px;
    }
    
    .audio-features {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }
    
    .feature-item {
        background-color: var(--spotify-dark-gray);
        border-radius: 8px;
        padding: 16px;
    }
    
    .feature-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 4px;
    }
    
    .feature-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--spotify-off-white);
        margin-bottom: 16px;
    }
    
    .feature-progress {
        margin-bottom: 16px;
    }
    
    .feature-description {
        font-size: 0.9rem;
        color: var(--spotify-off-white);
    }
    
    @media (max-width: 768px) {
        .track-details-section {
            flex-direction: column;
        }
        
        .track-image-container,
        .track-info-container {
            flex: 1 0 100%;
        }
        
        .track-title {
            font-size: 1.8rem;
        }
        
        .track-actions {
            flex-wrap: wrap;
        }
        
        .audio-features {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush