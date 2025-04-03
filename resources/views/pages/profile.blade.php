@extends('layouts.app')

@section('title', 'Profil - ' . $userProgress->getUsername())

@section('content')
<div class="profile-container">
    <!-- En-tête du profil -->
    <div class="profile-header">
        <div class="profile-image">
            <div class="profile-avatar">
                {{ substr($userProgress->getUsername(), 0, 1) }}
            </div>
        </div>
        
        <div class="profile-info">
            <h1 class="profile-name">{{ $userProgress->getUsername() }}</h1>
            <div class="profile-badge">
                <div class="level-indicator">{{ $userProgress->getLevel() }}</div>
            </div>
            
            <div class="profile-bio">
                Salut moi c'est {{ $userProgress->getUsername() }} et j'aime le rap
            </div>
            
            <div class="profile-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $userProgress->getProgressPercentage() }}%"></div>
                </div>
                <div class="progress-info">
                    <span>{{ $userProgress->getCurrentPoints() }} / {{ $userProgress->getMaxPoints() }} SPOINTS</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenu du profil -->
    <div class="profile-content">
        <!-- Playlists -->
        <div class="profile-section">
            <h2 class="section-title" style="color: var(--spotify-green);">MES PLAYLISTS</h2>
            <div class="playlists-container">
                @foreach($userPlaylists as $playlist)
                <div class="playlist-item">
                    <div class="playlist-cover">
                        <img src="{{ $playlist->getImageUrl() }}" alt="{{ $playlist->getName() }}">
                        <button class="play-btn-overlay">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    <h4 class="playlist-name">Playlist 1</h4>
                    <p style="margin-left: 10px;">Par Hugo</p>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Badges -->
        <div class="profile-section badges-section">
            <h2 class="section-title" style="color: var(--spotify-green);">MES BADGES</h2>
            <div class="badges-container">
                <div class="badge-grid">
                    <div class="badge-item">
                        <div class="badge-icon">
                            <img src="/img/badge-1.png" alt="" style="width: 100px;display:flex; align-items: center;">
                        </div>
                    </div>
                    <div class="badge-item">
                        <div class="badge-icon">
                            <img src="/img/badge-2.png" alt="" style="width: 100px;display:flex; align-items: center;">
                        </div>
                    </div>
                    <div class="badge-item">
                        <div class="badge-icon">
                            <img src="/img/badge-3.png" alt="" style="width: 100px;display:flex; align-items: center;">

                        </div>
                    </div>
                    <div class="badge-item">
                        <div class="badge-icon">
                            <img src="/img/badge-4.png" alt="" style="width: 100px;display:flex; align-items: center;">
                        </div>
                    </div>

                    <div class="badge-item">
                        <div class="badge-icon">
                            <img src="/img/badge-5.png" alt="" style="width: 100px;display:flex; align-items: center;">
                        </div>
                    </div>
                            
                    <div class="badge-item">
                        <div class="badge-icon">
                            <img src="/img/badge-6.png" alt="" style="width: 100px;display:flex; align-items: center;">
                        </div>
                    </div>
                            
                </div>
            </div>
        </div>
        
        <!-- Mix -->
        <div class="profile-section">
            <h2 class="section-title" style="color: var(--spotify-green);">MES MIX</h2>
            <div class="mix-container">
                @for($i = 1; $i <= 5; $i++)
                <div class="mix-item">
                    <div class="mix-cover">
                        <img src="/img/covers/mix-{{$i}}.jpg" alt="Mix {{$i}}">
                    </div>
                    <div class="mix-title">This is {{ ['Akira Yamaoka', 'Nobuo Uematsu', 'GFRIEND', '阿保剛', 'Nier'][$i-1] }}</div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-container {
        padding: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 50px;
    }
    
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background-color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 72px;
        font-weight: bold;
        color: white;
        margin-right: 30px;
    }
    
    .profile-info {
        flex: 1;
    }
    
    .profile-name {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .profile-badge {
        display: inline-block;
        margin-bottom: 15px;
    }
    
    .level-indicator {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: var(--spotify-green);
        border-radius: 50%;
        font-weight: bold;
        font-size: 18px;
    }
    
    .profile-bio {
        margin-bottom: 15px;
        font-size: 16px;
        color: var(--spotify-off-white);
    }
    
    .profile-progress {
        max-width: 400px;
    }
    
    .progress-bar {
        height: 6px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 5px;
    }
    
    .progress-fill {
        height: 100%;
        background-color: var(--spotify-green);
        border-radius: 3px;
    }
    
    .progress-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: var(--spotify-off-white);
    }
    
    .profile-content {
        display: grid;
        grid-template-columns: 3fr 1fr;
        grid-template-rows: auto auto;
        gap: 30px;
    }
    
    .profile-section {
        background-color: rgba(40, 40, 40, 0.6);
        border-radius: 8px;
        padding: 20px;
    }
    
    .section-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        color: white;
    }
    
    .playlists-container {
        display: flex;
        gap: 15px;
        padding-bottom: 10px;
    }
    
    .playlist-item {
        flex: 0 0 auto;
    }
    
    .playlist-cover img {
        width: 120px;
        height: 120px;
        border-radius: 5%;
        object-fit: cover;
    }

    .play-btn-overlay {
        position: absolute;
        bottom: 15px;
        right: 8px;
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

    .playlist-cover:hover .play-btn-overlay {
        opacity: 1;
        transform: translateY(0);
    }

    .playlist-name{
        font-size: 120%;
        margin-left: 5px;
    }
    
    .badges-section {
        grid-column: 2;
        grid-row: 1;
    }
    
    .badge-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(3, 1fr);
        gap: 10px;
    }
    
    .badge-item {
        background-color: rgba(60, 60, 60, 0.6);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        aspect-ratio: 1;
    }
    
    .badge-icon {
        font-size: 24px;
    }
    
    .mix-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .mix-item {
        display: flex;
        align-items: center;
        background-color: rgba(60, 60, 60, 0.5);
        border-radius: 5px;
        padding: 10px;
    }
    
    .mix-cover img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        margin-right: 15px;
    }
    
    .mix-title {
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-avatar {
            margin-right: 0;
            margin-bottom: 20px;
        }
        
        .profile-content {
            grid-template-columns: 1fr;
        }
        
        .badges-section {
            grid-column: 1;
            grid-row: 2;
        }
    }
</style>
@endpush