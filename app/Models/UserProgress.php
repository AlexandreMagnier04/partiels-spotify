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
     * Retourne les points nécessaires pour atteindre un niveau
     */
    public function getPointsForLevel(int $level)
    {
        // Exemple simple: 1000 points par niveau
        return $level * 1000;
    }
    
    /**
     * Retourne le niveau correspondant à un palier
     */
    public function getTierForLevel(int $level)
    {
        if ($level < 5) return 'Bronze';
        if ($level < 10) return 'Argent';
        if ($level < 15) return 'Or';
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
    
    /**
     * Retourne la couleur associée au niveau actuel
     */
    public function getTierColor()
    {
        $tier = $this->getTierForLevel($this->level);
        return $this->tier_colors[$tier] ?? '#1DB954'; // Couleur Spotify par défaut
    }
}