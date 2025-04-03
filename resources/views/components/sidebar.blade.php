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
            <img src="img\playlist-1.png" alt="playlist 1">
        </a>
        <a href="#" class="playlist-link">Playlist</a>
        <a href="#" class="playlist-link">Playlist 3</a>
        <a href="#" class="playlist-link">Playlist 4</a>
    </div>
</div>

<style>
    /* Sidebar modifiée */
    .sidebar {
        width: var(--sidebar-width);
        height: 100%;
        background-color: var(--spotify-darker-black);
        padding: 24px 12px;
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-logo {
        margin-bottom: 25px;
        padding: 0 12px;
    }
    
    .sidebar-logo img {
        width: 130px;
        height: auto;
    }
    
    .nav-menu {
        list-style: none;
        margin-bottom: 24px;
    }
    
    .nav-item {
        margin-bottom: 12px;
    }
    
    .nav-link {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        text-decoration: none;
    }
    
    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
    }
    
    .nav-link i {
        margin-right: 16px;
        font-size: 20px;
    }
    
    .playlist-actions {
        margin-bottom: 20px;
    }
    
    .create-playlist-btn, .liked-songs-btn {
        display: flex;
        align-items: center;
        background: none;
        border: none;
        color: var(--spotify-white);
        padding: 8px 12px;
        border-radius: 4px;
        margin-bottom: 10px;
        width: 100%;
        text-align: left;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .create-playlist-btn:hover, .liked-songs-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .create-playlist-btn i, .liked-songs-btn i {
        margin-right: 16px;
        font-size: 18px;
    }
    
    .playlist-divider {
        height: 1px;
        background-color: rgba(255, 255, 255, 0.1);
        margin: 12px 0 16px;
    }
    
    .user-playlists {
        overflow-y: auto;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .playlist-link {
        color: var(--spotify-off-white);
        padding: 6px 12px;
        display: block;
        border-radius: 4px;
        margin-bottom: 5px;
        transition: color 0.2s, background-color 0.2s;
    }
    
    .playlist-link:hover {
        color: var(--spotify-white);
        background-color: rgba(255, 255, 255, 0.1);
        text-decoration: none;
    }
    
    /* Responsive pour sidebar */
    @media (max-width: 768px) {
        .sidebar {
            width: 64px;
            padding: 16px 8px;
        }
        
        .sidebar-logo {
            display: none;
        }
        
        .nav-link, .create-playlist-btn, .liked-songs-btn {
            justify-content: center;
            padding: 12px;
        }
        
        .nav-link i, .create-playlist-btn i, .liked-songs-btn i {
            margin-right: 0;
            font-size: 24px;
        }
        
        .nav-link span, .create-playlist-btn span, .liked-songs-btn span, .playlist-link {
            display: none;
        }
    }
</style>