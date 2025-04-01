@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="main-content">
    <!-- SPOTVIP Progress -->
    <div class="spotvip-container">
        <div class="spotvip-title">
            <h2>SPOT'VIP</h2>
            <span class="spotvip-badge">Niveau {{ $userProgress->getLevel() }}</span>
        </div>
        <div class="spotvip-progress">
            <div class="spotvip-bar" style="width: {{ $userProgress->getProgressPercentage() }}%;"></div>
        </div>
        <div class="spotvip-stats">
            <span>{{ $userProgress->getCurrentPoints() }} SPOINTS</span>
            <span>{{ $userProgress->getMaxPoints() }} SPOINTS</span>
        </div>
    </div>

    <!-- Playlists -->
    <div class="section">
        <h2 class="section-title">Mes playlist</h2>
        <div class="playlist-grid">
            @foreach($userPlaylists as $playlist)
            <div class="playlist-card">
                <div class="playlist-cover-container">
                    @foreach($playlist->getCovers() as $index => $cover)
                        @if($index < 6)
                        <img src="{{ $cover }}" alt="Cover" class="playlist-cover">
                        @endif
                    @endforeach
                </div>
                <div class="playlist-info">
                    <div class="playlist-title">{{ $playlist->getName() }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Favorite Albums -->
    <div class="section">
        <h2 class="section-title">Mes favoris</h2>
        <div class="favorites-grid">
            @foreach($userFavorites as $album)
            <div class="favorite-card">
                <img src="{{ $album->getImageUrl() }}" alt="{{ $album->getName() }}" class="favorite-cover">
            </div>
            @endforeach
        </div>
    </div>

    <!-- New Releases -->
    <div class="section">
        <h2 class="section-title">Nouveautés</h2>
        <div class="favorites-grid">
            @foreach($newReleases as $album)
            <div class="favorite-card">
                <a href="{{ route('albumDetails', ['id' => $album->getId()]) }}">
                    <img src="{{ $album->getImageUrl() }}" alt="{{ $album->getName() }}" class="favorite-cover">
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Featured Playlists -->
    <div class="section">
        <h2 class="section-title">Playlists tendances</h2>
        <div class="favorites-grid">
            @foreach($featuredPlaylists as $playlist)
            <div class="favorite-card">
                <img src="{{ $playlist->getImageUrl() }}" alt="{{ $playlist->getName() }}" class="favorite-cover">
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection