<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'current_points',
        'max_points',
        'total_points'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'level' => 'integer',
            'current_points' => 'integer',
            'max_points' => 'integer',
            'total_points' => 'integer',
        ];
    }
    
    public function getProgressData()
    {
        $rewards = [
            ['level' => 5, 'reward' => 'Thèmes et couleurs de l’interface ', 'unlocked' => true],
            ['level' => 10, 'reward' => 'Places pour concert exclusif', 'unlocked' => true],
            ['level' => 15, 'reward' => 'Préventes de billets !', 'unlocked' => true],
            ['level' => 20, 'reward' => 'Premium 1 mois offert', 'unlocked' => false],
            ['level' => 25, 'reward' => 'Merchandising édition limitée', 'unlocked' => false],
        ];
        
        $activities = [
            'listening_hours' => 487,
            'playlists_created' => 12,
            'early_access_albums' => 4,
            'events_participated' => 2,
            'artist_interactions' => 9
        ];
        
        $history = [
            [
                'timestamp' => time() - 3600 * 24 * 3,
                'action' => 'Écoute de l\'album "Nevermind" de Nirvana',
                'points' => 15
            ],
            [
                'timestamp' => time() - 3600 * 24 * 2,
                'action' => 'Création de la playlist "Summer Vibes 2025"',
                'points' => 50
            ],
            [
                'timestamp' => time() - 3600 * 24,
                'action' => 'Participation au quiz musical',
                'points' => 75
            ],
            [
                'timestamp' => time() - 3600 * 3,
                'action' => 'Écoute de 3 heures de musique',
                'points' => 3
            ]
        ];
        
    
        
        $level = 16;
        $currentPoints = 700; 
        $maxPoints = 1000;
        
        return new UserProgress(
            $this->id,
            $this->name,
            $currentPoints,
            $maxPoints,
            $level,
            $activities,
            $rewards,
            $history
        );
    }
}