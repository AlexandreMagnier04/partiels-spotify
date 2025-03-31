<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify - @yield('title', 'Accueil')</title>
    
    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/spotify-theme.css" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes - peut être remplacé par vos propres icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="spotify-theme">
    <div class="container">
        <div class="app-layout">
            <!-- Sidebar -->
            <aside class="sidebar-wrapper">
                @include('components.sidebar')
            </aside>
            
            <!-- Contenu principal -->
            <main class="main-content">
                <!-- Header de navigation -->
                <header class="main-header">
                    <div class="navigation-controls">
                        <button class="btn-circle btn-dark"><i class="fas fa-chevron-left"></i></button>
                        <button class="btn-circle btn-dark"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    
                    <div class="user-controls">
                        <button class="btn-outline">Premium</button>
                        <div class="user-dropdown">
                            <button class="btn-circle btn-dark user-menu-button">
                                <i class="fas fa-user"></i>
                            </button>
                            <div class="dropdown-menu" id="userDropdown">
                                <a href="#" class="dropdown-item">Profil</a>
                                <a href="#" class="dropdown-item">Paramètres</a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">Déconnexion</a>
                            </div>
                        </div>
                    </div>
                </header>
                
                <!-- Contenu de la page -->
                <div class="page-content">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- Lecteur audio fixe en bas -->
    <footer class="player-bar">
        @include('components.player')
    </footer>
    
    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="/js/player.js"></script>
    <script src="/js/personalization.js"></script>
    
    @stack('scripts')
</body>
</html>