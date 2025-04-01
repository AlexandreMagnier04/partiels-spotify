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
     * Retourne les points nécessaires pour atteindre le niveau suivant
     */
    public function getSpointsToNextLevel()
    {
        return $this->max_points - $this->current_points;
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
    
    /**
     * Retourne la couleur associée au niveau actuel
     */
    public function getTierColor()
    {
        $tier = $this->getTierForLevel($this->level);
        return $this->tier_colors[$tier] ?? '#1DB954'; // Couleur Spotify par défaut
    }
    
    /**
     * Retourne le palier correspondant au niveau
     */
    public function getTierForLevel(int $level)
    {
        if ($level < 5) return 'Bronze';
        if ($level < 10) return 'Argent';
        if ($level < 15) return 'Or';
        return 'Platine';
    }

    /**
 * Calcule les points nécessaires pour atteindre un niveau spécifique
 * 
 * @param int $level Le niveau cible
 * @return int Le nombre de points nécessaires
 */
    public function getPointsForLevel($level)
    {
    // Calcul simple pour les points requis par niveau
    // 10000 points pour le niveau 1, puis augmentation de 5000 par niveau
    $basePoints = 10000;
    $incrementPerLevel = 5000;
    
    return $basePoints + ($level - 1) * $incrementPerLevel;
    }
}