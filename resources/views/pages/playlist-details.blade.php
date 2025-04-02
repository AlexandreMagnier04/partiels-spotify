@extends('layouts.app')

@section('title', $playlist->getName())

@section('content')
<div class="playlist-details-container">
    <div class="playlist-header">
        <div class="playlist-cover">
            <img src="{{ $playlist->getImageUrl() }}" alt="{{ $playlist->getName() }}" class="playlist-cover-img">
        </div>
        <div class="playlist-info">
            <div class="playlist-type">Playlist</div>
            <h1 class="playlist-title">{{ $playlist->getName() }}</h1>
            <div class="playlist-description">{{ $playlist->getDescription() }}</div>
            <div class="playlist-meta">
                <span class="playlist-owner">{{ $playlist->getOwner() }}</span>
                <span class="playlist-stats">{{ $playlist->getTracksCount() }} titres, environ {{ $totalDuration }}</span>
            </div>
        </div>
    </div>
    
    <div class="playlist-btns">
        <button class="play-button">
            <i class="fas fa-play"></i>
        </button>
        <div style="display: flex;">
            <button class="favorite-button">
                <i class="far fa-heart"></i>
            </button>
            <div class="more-options">
                <i class="fas fa-ellipsis-h"></i>
            </div>
        </div>
    </div>
    
    <div class="tracks-list-header">
        <div class="track-number">#</div>
        <div class="track-title">Titre</div>
        <div class="track-album">Album</div>
        <div class="track-date">Date d'ajout</div>
        <div class="track-duration"><i class="far fa-clock"></i></div>
    </div>
    
    <div class="tracks-list">
        @foreach($tracks as $index => $track)
        <div class="track-item">
            <div class="track-number">{{ $index + 1 }}</div>
            <div class="track-title-info">
                <img src="{{ isset($track->image_url) ? $track->image_url : (method_exists($track, 'getImageUrl') ? $track->getImageUrl() : 'public\img\album-1.png') }}" alt="{{ $track->name }}" class="track-cover">
                <div class="track-details">
                    <div class="track-name">{{ $track->name }}</div>
                    <div class="track-artist">{{ $track->artist }}</div>
                </div>
                <div class="play-track-overlay">
                    <i class="fas fa-play"></i>
                </div>
            </div>
            <div class="track-album">{{ isset($track->album_name) ? $track->album_name : (method_exists($track, 'getAlbumName') ? $track->getAlbumName() : 'Album') }}</div>
            <div class="track-date">Il y a {{ rand(1, 30) }} jours</div>
            <div class="track-duration">
                @php
                    $duration = isset($track->duration_ms) ? $track->duration_ms : (method_exists($track, 'getDurationMs') ? $track->getDurationMs() : 210000);
                    $minutes = floor($duration / 60000);
                    $seconds = floor(($duration % 60000) / 1000);
                    echo $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
                @endphp
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
    .playlist-details-container {
        padding: 0 30px 40px;
    }
    
    .playlist-header {
        display: flex;
        margin-bottom: 24px;
        padding-top: 20px;
    }
    
    .playlist-cover {
        width: 232px;
        height: 232px;
        margin-right: 24px;
        box-shadow: 0 4px 60px rgba(0, 0, 0, 0.5);
    }
    
    .playlist-cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .playlist-info {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }
    
    .playlist-type {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .playlist-title {
        font-size: 48px;
        font-weight: 900;
        margin-bottom: 16px;
        color: var(--spotify-white);
    }
    
    .playlist-description {
        font-size: 16px;
        margin-bottom: 8px;
        color: var(--spotify-off-white);
        max-width: 800px;
    }
    
    .playlist-meta {
        font-size: 14px;
        color: var(--spotify-off-white);
    }
    
    .playlist-owner {
        font-weight: 700;
        color: var(--spotify-white);
    }
    
    .playlist-owner::after {
        content: '•';
        margin: 0 4px;
    }
    
    .playlist-btns {
        display: flex;
        align-items: center;
        margin-bottom: 32px;
        justify-content: space-between;
    }
    
    .play-button {
        width: 56px;
        height: 56px;
        background-color: var(--spotify-green);
        border-radius: 50%;
        border: none;
        color: black;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 32px;
        cursor: pointer;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    
    .play-button:hover {
        transform: scale(1.05);
        background-color: #1ed760;
    }
    
    .favorite-button {
        background: transparent;
        border: none;
        color: var(--spotify-off-white);
        font-size: 22px;
        margin-right: 16px;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    
    .favorite-button:hover {
        color: var(--spotify-white);
    }
    
    .more-options {
        color: var(--spotify-off-white);
        font-size: 22px;
        cursor: pointer;
    }
    
    .more-options:hover {
        color: var(--spotify-white);
    }
    
    .tracks-list-header {
        display: grid;
        grid-template-columns: 50px 4fr 2fr 2fr 1fr;
        padding: 0 16px 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--spotify-off-white);
        font-size: 14px;
        font-weight: 400;
    }
    
    .tracks-list {
        margin-top: 16px;
    }
    
    .track-item {
        display: grid;
        grid-template-columns: 50px 4fr 2fr 2fr 1fr;
        padding: 8px 16px;
        border-radius: 4px;
        align-items: center;
        color: var(--spotify-off-white);
        transition: background-color 0.2s ease;
        transition: 0.3s all ease-in-out;
    }
    
    .track-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: scale(107%);
    }
    

    
    .track-item:hover .play-track-overlay {
        display: flex;
    }

    .track-title-info {
        display: flex;
        align-items: center;
        position: relative;
    }
    
    .track-cover {
        width: 40px;
        height: 40px;
        margin-right: 16px;
        object-fit: cover;
    }
    
    .track-details {
        display: flex;
        flex-direction: column;
    }
    
    .track-name {
        color: var(--spotify-white);
        margin-bottom: 4px;
        font-weight: 400;
    }
    
    .track-artist {
        font-size: 14px;
    }
    
    .play-track-overlay {
        position: absolute;
        left: 6px;
        top: 6px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: var(--spotify-green);
        color: var(--spotify-black);
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .play-track-overlay:hover{
        cursor: pointer;
    }
    
    .track-album, .track-date {
        font-size: 14px;
    }
    
    .track-duration {
        text-align: right;
        font-size: 14px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .playlist-header {
            flex-direction: column;
        }
        
        .playlist-cover {
            width: 192px;
            height: 192px;
            margin: 0 auto 24px;
        }
        
        .playlist-info {
            align-items: center;
            text-align: center;
        }
        
        .playlist-title {
            font-size: 32px;
        }
        
        .tracks-list-header, .track-item {
            grid-template-columns: 50px 1fr 80px;
        }
        
        .track-album, .track-date {
            display: none;
        }
        .playlist-details-container{
            padding: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation pour le bouton de lecture principale
        const playButton = document.querySelector('.play-button');
        if (playButton) {
            playButton.addEventListener('click', function() {
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
        
        // Animation pour le bouton de favoris
        const favoriteButton = document.querySelector('.favorite-button');
        if (favoriteButton) {
            favoriteButton.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.style.color = '#1DB954';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    icon.style.color = '';
                }
            });
        }
        
        // Animation pour les pistes individuelles
        const trackItems = document.querySelectorAll('.track-item');
        trackItems.forEach(track => {
            track.addEventListener('click', function() {
                // Simuler la lecture
                const playIcon = this.querySelector('.play-track-overlay i');
                if (playIcon.classList.contains('fa-play')) {
                    // Réinitialiser tous les autres icônes
                    document.querySelectorAll('.play-track-overlay i').forEach(icon => {
                        icon.classList.remove('fa-pause');
                        icon.classList.add('fa-play');
                    });
                    
                    // Mettre à jour l'icône actuel
                    playIcon.classList.remove('fa-play');
                    playIcon.classList.add('fa-pause');
                } else {
                    playIcon.classList.remove('fa-pause');
                    playIcon.classList.add('fa-play');
                }
            });
        });
    });
</script>
@endpush