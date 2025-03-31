<div class="sidebar">
    <!-- Logo -->
    <div class="logo-container mb-4 pt-3">
        <a href="{{ route('home') }}">
            <img src="/img/icons/spotify-logo.png" alt="Spotify" class="img-fluid" width="140">
        </a>
    </div>
    
    <!-- Navigation principale -->
    <nav class="main-nav mb-4">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="fas fa-home me-2"></i> Accueil
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}" href="{{ route('search') }}">
                    <i class="fas fa-search me-2"></i> Rechercher
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('library') ? 'active' : '' }}" href="{{ route('library') }}">
                    <i class="fas fa-book me-2"></i> Bibliothèque
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- Actions -->
    <div class="sidebar-actions mb-4">
        <button class="btn btn-outline-light btn-sm d-flex align-items-center mb-2 w-100">
            <i class="fas fa-plus-square me-2"></i> Créer une playlist
        </button>
        <button class="btn btn-outline-light btn-sm d-flex align-items-center w-100">
            <i class="fas fa-heart me-2"></i> Titres likés
        </button>
    </div>
    
    <!-- Playlists -->
    <div class="sidebar-playlists">
        <hr class="my-3 bg-secondary">
        <h6 class="sidebar-heading text-uppercase mb-2">Playlists</h6>
        <div class="playlist-list" style="max-height: 300px; overflow-y: auto;">
            <ul class="nav flex-column small">
                <li class="nav-item">
                    <a class="nav-link text-muted" href="#">Mes titres préférés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted" href="#">Playlist Sport</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted" href="#">Soirée</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted" href="#">Ambiance Chill</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted" href="#">Concentration</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted" href="#">Hits 2010</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted" href="#">Roadtrip</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted" href="#">Summer Vibes</a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Installer l'app -->
    <div class="sidebar-install mt-4">
        <a class="nav-link text-muted d-flex align-items-center" href="#">
            <i class="fas fa-arrow-down me-2"></i> Installer l'application
        </a>
    </div>
</div>