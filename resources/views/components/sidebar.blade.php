<nav class="sidebar">
    <!-- Logo -->
    <div class="logo-container">
        <a href="{{ route('home') }}">
            <img src="/img/icons/spotify-logo.png" alt="Spotify" width="140">
        </a>
    </div>
    
    <!-- Navigation principale -->
    <div class="main-nav">
        <ul>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="fas fa-home"></i> Accueil
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}" href="{{ route('search') }}">
                    <i class="fas fa-search"></i> Rechercher
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('library') ? 'active' : '' }}" href="{{ route('library') }}">
                    <i class="fas fa-book"></i> Bibliothèque
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Actions -->
    <div class="sidebar-actions">
        <button class="btn-outline create-playlist">
            <i class="fas fa-plus-square"></i> Créer une playlist
        </button>
        <button class="btn-outline liked-songs">
            <i class="fas fa-heart"></i> Titres likés
        </button>
    </div>
    
    <!-- Playlists -->
    <div class="sidebar-playlists">
        <hr class="sidebar-divider">
        <h6 class="sidebar-heading">Playlists</h6>
        <div class="playlist-list">
            <ul>
                <li class="nav-item">
                    <a class="nav-link" href="#">Mes titres préférés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Playlist Sport</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Soirée</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Ambiance Chill</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Concentration</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Hits 2010</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Roadtrip</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Summer Vibes</a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Installer l'app -->
    <div class="sidebar-install">
        <a class="nav-link" href="#">
            <i class="fas fa-arrow-down"></i> Installer l'application
        </a>
    </div>
</nav>