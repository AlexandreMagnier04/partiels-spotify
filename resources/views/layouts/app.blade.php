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
    <!-- Overlay pour le menu sur mobile -->
    <div class="sidebar-overlay"></div>
    
    <div class="main-container">
        <div class="content-wrapper">
            <!-- Sidebar - maintenant avec la classe pour l'animation -->
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
                    <a href="#" class="playlist-link">
                    <img class="sidebar-playlist" src="img\album-8.png" alt="playlist 1">
                    <span></span>
                    </a>
                    <a href="#" class="playlist-link">
                    <img class="sidebar-playlist" src="img\album-3.png" alt="playlist 1">
                    </a>
                    <a href="#" class="playlist-link">
                    <img class="sidebar-playlist" src="img\lbum-1.png" alt="playlist 1">
                    </a>
                    <a href="#" class="playlist-link">
                    <img class="sidebar-playlist" src="img\album-7.png" alt="playlist 1">
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="main-content">
                <!-- Barre de recherche avec bouton de menu -->
                <div class="top-search-container">
                    <!-- Bouton du menu burger -->
                    <button id="menu-toggle" class="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <form action="{{ route('search') }}" method="GET" id="search-form">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" name="q" placeholder="Que souhaitez-vous écouter ?" value="{{ $query ?? '' }}" id="search-input">
                        </div>
                    </form>
                    
                    <a href="{{ route('profile') }}" class="profile-link">
                        <div class="profile-icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </a>
                </div>
                
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
            
            // Script pour le menu burger
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const overlay = document.querySelector('.sidebar-overlay');
            
            // Fonction pour ouvrir le menu
            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                document.body.classList.add('sidebar-open');
                
                // Mémoriser l'état du menu dans le localStorage
                localStorage.setItem('sidebarState', 'open');
            }
            
            // Fonction pour fermer le menu
            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
                
                // Mémoriser l'état du menu dans le localStorage
                localStorage.setItem('sidebarState', 'closed');
            }
            
            // Écouteur d'événement pour le bouton du menu
            menuToggle.addEventListener('click', function() {
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
            
            // Fermer le menu quand on clique sur l'overlay
            overlay.addEventListener('click', closeSidebar);
            
            // Gestion de l'état initial du menu selon la taille de l'écran
            function handleInitialState() {
                const savedState = localStorage.getItem('sidebarState');
                
                if (window.innerWidth < 1024) {
                    // Sur mobile et tablette, le menu est fermé par défaut
                    closeSidebar();
                } else if (savedState === 'open' || savedState === null) {
                    // Sur desktop, le menu est ouvert par défaut ou selon la préférence enregistrée
                    openSidebar();
                } else {
                    closeSidebar();
                }
            }
            
            // Gestion du redimensionnement de l'écran
            window.addEventListener('resize', function() {
                if (window.innerWidth < 1024 && sidebar.classList.contains('open')) {
                    // Ferme automatiquement le menu sur mobile en cas de redimensionnement
                    closeSidebar();
                }
            });
            
            // CORRECTION: Gestionnaire pour la barre de recherche
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('click', function(event) {
                    // Empêcher la soumission du formulaire au clic
                    event.preventDefault();
                    // Si nous ne sommes pas déjà sur la page de recherche, naviguer vers elle
                    if (!window.location.href.includes('/search')) {
                        window.location.href = "{{ route('search', ['q' => '']) }}";
                    }
                });
            }
            
            // Initialisation de l'état du menu au chargement de la page
            handleInitialState();
        });
    </script>
    
    @stack('scripts')
</body>
</html>