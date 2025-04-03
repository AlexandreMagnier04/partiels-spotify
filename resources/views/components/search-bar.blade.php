<div class="top-search-container">
    <!-- Bouton du menu burger -->
    <button id="menu-toggle" class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <form action="{{ route('search') }}" method="GET" id="search-form">
        <div class="search-input-wrapper" style="align-items: center;">
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

<style>
    /* Style pour la barre de recherche avec menu burger */
    .top-search-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        padding: 16px 30px;
    }
    
    .menu-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: transparent;
        border: none;
        color: var(--spotify-white);
        cursor: pointer;
        transition: background-color 0.2s;
        margin-right: 16px;
    }
    
    .menu-toggle:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .menu-toggle i {
        font-size: 20px;
    }
    
    .search-input-wrapper {
        position: relative;
        width: 500px;
        max-width: 90%;
    }
    
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        z-index: 10;
        transform: translateY(-50%);
        color: var(--spotify-off-white);
        font-size: 16px;
    }
    
    .search-input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border-radius: 30px;
        border: none;
        background-color: var(--spotify-medium-gray);
        color: var(--spotify-white);
        font-size: 14px;
        cursor: pointer;
    }
    
    .search-input:focus {
        outline: none;
        background-color: var(--spotify-light-gray);
    }
    
    .search-input::placeholder {
        color: var(--spotify-off-white);
    }
    
    .profile-link {
        text-decoration: none;
    }
    
    .profile-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: var(--spotify-dark-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: background-color 0.3s;
    }
    
    .profile-icon:hover {
        background-color: var(--spotify-medium-gray);
    }
    
    /* Style responsive */
    @media (max-width: 768px) {
        .search-input-wrapper {
            width: 280px;
        }
        
        .top-search-container {
            padding: 12px 16px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');

        // Quand l'utilisateur clique sur la barre de recherche
        searchInput.addEventListener('click', function(event) {
            // Si nous ne sommes pas déjà sur la page de recherche, naviguons-y
            if (!window.location.href.includes('/search')) {
                window.location.href = "{{ route('search', ['q' => '']) }}";
            }
        });
    });
</script>