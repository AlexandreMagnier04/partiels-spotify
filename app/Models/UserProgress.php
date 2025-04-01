<?php

namespace App\Models;

class UserProgress
{
    private $user_id;
    private $username;
    private $current_points;
    private $max_points;
    private $level;
    private $activities;
    private $rewards;
    private $history;
    private $tier_colors = [
        'Bronze' => '#cd7f32',
        'Argent' => '#C0C0C0',
        'Or' => '#FFD700',
        'Platine' => '#e5e4e2'
    ];
    
    private $tiers = [
        'Bronze' => [
            'min_xp' => 5000,
            'max_xp' => 10000,
            'rewards' => [
                'Badges de profil personnalisés',
                'Sons d\'introduction exclusifs'
            ]
        ],
        'Argent' => [
            'min_xp' => 10001,
            'max_xp' => 15000,
            'rewards' => [
                'Accès anticipé à des concerts en streaming',
                'Partage de contenu exclusif',
                'Participation à des concours VIP',
                'Personnalisation de l\'interface Spotify'
            ]
        ],
        'Or' => [
            'min_xp' => 15001,
            'max_xp' => 20000,
            'rewards' => [
                'Accès à des sessions Q&A avec des artistes',
                'Accès à des préventes de billets pour concerts',
                'Goodies exclusifs',
                'Playlist personnalisée d\'artistes similaires'
            ]
        ],
        'Platine' => [
            'min_xp' => 20001,
            'max_xp' => 25000,
            'rewards' => [
                '1 mois d\'abonnement Premium gratuit',
                'Rencontres en ligne avec des artistes',
                'Accès à des événements VIP en personne',
                'Tableau de bord exclusif',
                'Mentorat musical'
            ]
        ]
    ];

    public function __construct(
        string $user_id,
        string $username,
        int $current_points,
        int $max_points,
        int $level,
        array $activities,
        array $rewards = [],
        array $history = []
    ) {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->current_points = $current_points;
        $this->max_points = $max_points;
        $this->level = $level;
        $this->activities = $activities;
        $this->rewards = $rewards;
        $this->history = $history;
    }
    
    /**
     * Ajoute des points au profil utilisateur
     */
    public function addPoints(int $points, string $action)
    {
        $this->current_points += $points;
        
        // Ajouter à l'historique
        $this->history[] = [
            'timestamp' => time(),
            'action' => $action,
            'points' => $points
        ];
        
        // Dans une vraie implémentation, on sauvegarderait en base de données
        return $this->current_points;
    }
    
    /**
     * Calcule le pourcentage de progression dans le niveau actuel
     */
    public function getProgressPercentage()
    {
        if ($this->max_points == 0) return 0; // Éviter division par zéro
        
        $percentage = ($this->current_points / $this->max_points) * 100;
        return min(100, $percentage); // Ne pas dépasser 100%
    }
    
    /**
     * Calcule le pourcentage de progression global pour la barre des récompenses
     */
    public function getRewardsProgressPercentage()
    {
        // Récupérer la dernière récompense (le niveau le plus élevé)
        $lastReward = end($this->rewards);
        $highestRewardLevel = $lastReward['level'] ?? $this->level + 5; // Valeur par défaut si pas de récompense
        
        // Calculer le nombre total de points nécessaires pour atteindre le niveau le plus élevé
        $totalPointsNeeded = $this->getPointsForLevel($highestRewardLevel);
        
        // Obtenir le nombre total de points actuels de l'utilisateur
        $currentTotalPoints = $this->getTotalPoints();
        
        // Calculer le pourcentage de progression
        if ($totalPointsNeeded == 0) return 0; // Éviter division par zéro
        
        $percentage = ($currentTotalPoints / $totalPointsNeeded) * 100;
        return min(100, $percentage); // Ne pas dépasser 100%
    }
    
    /**
     * Retourne les points nécessaires pour atteindre un niveau
     */
    public function getPointsForLevel(int $level)
    {
        // Exemple simple: 1000 points par niveau
        return $level * 1000;
    }
    
    /**
     * Retourne le palier correspondant au nombre de points XP total
     */
    public function getCurrentTier()
    {
        $totalPoints = $this->getTotalPoints();
        
        foreach ($this->tiers as $tierName => $tierData) {
            if ($totalPoints >= $tierData['min_xp'] && $totalPoints <= $tierData['max_xp']) {
                return $tierName;
            }
        }
        
        // Si l'utilisateur a plus de points que le dernier palier défini
        return array_key_last($this->tiers);
    }
    
    /**
     * Retourne le prochain palier à atteindre
     */
    public function getNextTier()
    {
        $currentTier = $this->getCurrentTier();
        $tierNames = array_keys($this->tiers);
        $currentIndex = array_search($currentTier, $tierNames);
        
        if ($currentIndex !== false && $currentIndex < count($tierNames) - 1) {
            return $tierNames[$currentIndex + 1];
        }
        
        // Déjà au palier maximum
        return $currentTier;
    }
    
    /**
     * Retourne le pourcentage de progression vers le prochain palier
     */
    public function getTierProgressPercentage()
    {
        $totalPoints = $this->getTotalPoints();
        $currentTier = $this->getCurrentTier();
        $nextTier = $this->getNextTier();
        
        // Déjà au palier maximum
        if ($currentTier === $nextTier) {
            return 100;
        }
        
        $currentTierMinXP = $this->tiers[$currentTier]['min_xp'];
        $nextTierMinXP = $this->tiers[$nextTier]['min_xp'];
        
        $pointsRange = $nextTierMinXP - $currentTierMinXP;
        $userProgress = $totalPoints - $currentTierMinXP;
        
        if ($pointsRange <= 0) return 100; // Protection
        
        $percentage = ($userProgress / $pointsRange) * 100;
        return min(100, $percentage); // Ne pas dépasser 100%
    }
    
    /**
     * Retourne les récompenses pour le palier actuel
     */
    public function getCurrentTierRewards()
    {
        $currentTier = $this->getCurrentTier();
        return $this->tiers[$currentTier]['rewards'] ?? [];
    }
    
    /**
     * Retourne les récompenses pour le prochain palier
     */
    public function getNextTierRewards()
    {
        $nextTier = $this->getNextTier();
        return $this->tiers[$nextTier]['rewards'] ?? [];
    }
    
    /**
     * Retourne les points restants pour atteindre le prochain palier
     */
    public function getPointsToNextTier()
    {
        $totalPoints = $this->getTotalPoints();
        $currentTier = $this->getCurrentTier();
        $nextTier = $this->getNextTier();
        
        // Déjà au palier maximum
        if ($currentTier === $nextTier) {
            return 0;
        }
        
        $nextTierMinXP = $this->tiers[$nextTier]['min_xp'];
        return max(0, $nextTierMinXP - $totalPoints);
    }
    
    /**
     * Retourne le niveau correspondant à un palier
     */
    public function getTierForLevel(int $level)
    {
        if ($level < 5) return 'Unranked';
        if ($level < 10) return 'Bronze';
        if ($level < 15) return 'Argent';
        if ($level < 20) return 'Or';
        return 'Platine';
    }
    
    /**
     * Getters
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getCurrentPoints()
    {
        return $this->current_points;
    }
    
    public function getMaxPoints()
    {
        return $this->max_points;
    }
    
    public function getLevel()
    {
        return $this->level;
    }
    
    public function getTotalPoints()
    {
        // Dans un vrai système, ce serait la somme de tous les points gagnés
        return $this->current_points + (($this->level - 1) * $this->max_points);
    }
    
    public function getActivities()
    {
        return $this->activities;
    }
    
    public function getRewards()
    {
        return $this->rewards;
    }
    
    public function getHistory()
    {
        return $this->history;
    }
    
    public function getTiers()
    {
        return $this->tiers;
    }
    
    /**
     * Retourne la couleur associée au niveau actuel
     */
    public function getTierColor(string $tier)
    {
        return $this->tier_colors[$tier] ?? '#1DB954'; // Couleur Spotify par défaut
    }
}