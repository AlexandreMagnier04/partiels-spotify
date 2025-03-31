/**
 * Application JS principal - Sans dépendances jQuery/Bootstrap
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des dropdowns
    initDropdowns();
    
    // Gestion des boutons de navigation
    initNavigation();
    
    // Animation des éléments
    initAnimations();
});

/**
 * Initialise les menus déroulants
 */
function initDropdowns() {
    // Gestion des clics pour ouvrir/fermer les dropdowns
    const dropdownButtons = document.querySelectorAll('.user-menu-button');
    
    dropdownButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;
            
            // Toggle du dropdown
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            } else {
                // Fermer tous les autres dropdowns ouverts
                const openDropdowns = document.querySelectorAll('.dropdown-menu');
                openDropdowns.forEach(menu => {
                    if (menu !== dropdown) {
                        menu.style.display = 'none';
                    }
                });
                
                dropdown.style.display = 'block';
            }
        });
    });
    
    // Fermer les dropdowns quand on clique ailleurs
    document.addEventListener('click', function(e) {
        const openDropdowns = document.querySelectorAll('.dropdown-menu');
        openDropdowns.forEach(menu => {
            if (!menu.parentElement.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    });
}

/**
 * Initialise les boutons de navigation
 */
function initNavigation() {
    // Boutons précédent/suivant dans l'historique
    const backButton = document.querySelector('.navigation-controls button:first-child');
    const forwardButton = document.querySelector('.navigation-controls button:last-child');
    
    if (backButton) {
        backButton.addEventListener('click', function() {
            window.history.back();
        });
    }
    
    if (forwardButton) {
        forwardButton.addEventListener('click', function() {
            window.history.forward();
        });
    }
}

/**
 * Initialise les animations UI
 */
function initAnimations() {
    // Animation des cartes au chargement
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s, transform 0.5s';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 + (index * 50)); // Décalage pour effet cascade
    });
    
    // Animation du header au scroll
    const mainHeader = document.querySelector('.main-header');
    if (mainHeader) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                mainHeader.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
                mainHeader.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.3)';
            } else {
                mainHeader.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                mainHeader.style.boxShadow = 'none';
            }
        });
    }
}

/**
 * Ajoute des styles CSS supplémentaires pour la page d'accueil
 */
function addHomeStyles() {
    const stylesheet = document.createElement('style');
    stylesheet.textContent = `
    .shortcuts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }
    
    .shortcut-card {
        display: flex;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        overflow: hidden;
        position: relative;
        height: 80px;
        transition: background-color 0.3s;
    }
    
    .shortcut-card:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }
    
    .shortcut-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
    }
    
    .shortcut-info {
        padding: 0 16px;
    }
    
    .shortcut-info h5 {
        margin: 0;
        font-size: 0.9rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
    }
    `;
    
    document.head.appendChild(stylesheet);
}

// Appliquer les styles spécifiques à la page d'accueil si nécessaire
if (document.querySelector('.welcome-section')) {
    addHomeStyles();
}