<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify - @yield('title', 'Accueil')</title>
    
    <!-- Styles -->
    <link href="{{ asset('css/spotify-theme.css') }}" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body>
    <div class="main-container">
        <div class="content-wrapper">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="sidebar-logo">
                    <a href="{{ route('home') }}">
                        <img src="https://storage.googleapis.com/pr-newsroom-wp/1/2018/11/Spotify_Logo_RGB_White.png" alt="Spotify">
                    </a>
                </div>
                
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('search', ['q' => '']) }}" class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}">
                            <i class="fas fa-search"></i>
                            <span>Rechercher</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('library') }}" class="nav-link {{ request()->routeIs('library') ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            <span>Bibliothèque</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('progression') }}" class="nav-link {{ request()->routeIs('progression') ? 'active' : '' }}">
                            <i class="fas fa-trophy"></i>
                            <span>SPOT'VIP</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                            <i class="fas fa-user"></i>
                            <span>Profil</span>
                        </a>
                    </li>
                </ul>
                
                <div class="playlist-actions">
                    <button class="create-playlist-btn">
                        <i class="fas fa-plus"></i>
                        <span>Créer une playlist</span>
                    </button>
                    <button class="liked-songs-btn">
                        <i class="fas fa-heart"></i>
                        <span>Titres likés</span>
                    </button>
                </div>
                
                <div class="playlist-divider"></div>
                
                <div class="user-playlists">
                    <a href="#" class="playlist-link">Playlist 1</a>
                    <a href="#" class="playlist-link">Playlist 2</a>
                    <a href="#" class="playlist-link">Playlist 3</a>
                    <a href="#" class="playlist-link">Playlist 4</a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>
        </div>
        
        <!-- Player -->
        <div class="player">
            <div class="player-left">
                <img src="{{ asset('img/covers/album1.jpg') }}" class="now-playing-cover" alt="Now Playing">
                <div class="now-playing-info">
                    <div class="now-playing-title">Snooze</div>
                    <div class="now-playing-artist">SZA</div>
                </div>
                <div class="now-playing-actions">
                    <button class="action-button">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
            </div>
            
            <div class="player-center">
                <div class="player-controls">
                    <button class="control-button">
                        <i class="fas fa-random"></i>
                    </button>
                    <button class="control-button">
                        <i class="fas fa-step-backward"></i>
                    </button>
                    <button class="control-button play-pause">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="control-button">
                        <i class="fas fa-step-forward"></i>
                    </button>
                    <button class="control-button">
                        <i class="fas fa-retweet"></i>
                    </button>
                </div>
                
                <div class="player-progress">
                    <div class="progress-time">0:45</div>
                    <div class="progress-bar">
                        <div class="progress-filled" style="width: 25%;"></div>
                    </div>
                    <div class="progress-time">3:22</div>
                </div>
            </div>
            
            <div class="player-right">
                <div class="volume-control">
                    <i class="fas fa-volume-up volume-icon"></i>
                    <div class="volume-bar">
                        <div class="volume-level" style="width: 70%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lecteur audio
            const playPauseBtn = document.querySelector('.play-pause');
            if (playPauseBtn) {
                playPauseBtn.addEventListener('click', function() {
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
            
            // Barres de progression
            const progressBar = document.querySelector('.progress-bar');
            if (progressBar) {
                progressBar.addEventListener('click', function(e) {
                    const width = this.clientWidth;
                    const clickX = e.offsetX;
                    const percent = (clickX / width) * 100;
                    
                    const progressFilled = this.querySelector('.progress-filled');
                    progressFilled.style.width = percent + '%';
                });
            }
            
            // Volume
            const volumeBar = document.querySelector('.volume-bar');
            if (volumeBar) {
                volumeBar.addEventListener('click', function(e) {
                    const width = this.clientWidth;
                    const clickX = e.offsetX;
                    const percent = (clickX / width) * 100;
                    
                    const volumeLevel = this.querySelector('.volume-level');
                    volumeLevel.style.width = percent + '%';
                });
            }
            
            // Animation des cartes
            const cards = document.querySelectorAll('.content-card, .playlist-card, .album-card, .favorite-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>