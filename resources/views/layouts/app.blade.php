<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify - @yield('title', 'Accueil')</title>
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/spotify-theme.css" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="spotify-theme">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar-wrapper">
                @include('components.sidebar')
            </div>
            
            <!-- Contenu principal -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header de navigation -->
                <header class="d-flex justify-content-between align-items-center py-3">
                    <div class="navigation-controls">
                        <button class="btn btn-circle btn-dark me-2"><i class="fas fa-chevron-left"></i></button>
                        <button class="btn btn-circle btn-dark"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    
                    <div class="user-controls">
                        <button class="btn btn-outline-light me-2">Premium</button>
                        <div class="dropdown">
                            <button class="btn btn-circle btn-dark dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li><a class="dropdown-item" href="#">Paramètres</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Déconnexion</a></li>
                            </ul>
                        </div>
                    </div>
                </header>
                
                <!-- Contenu de la page -->
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    
    <!-- Lecteur audio fixe en bas -->
    <footer class="player-bar">
        @include('components.player')
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/app.js"></script>
    <script src="/js/player.js"></script>
    <script src="/js/personalization.js"></script>
    
    @stack('scripts')
</body>
</html>