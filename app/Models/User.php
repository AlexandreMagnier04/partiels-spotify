<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'current_points',
        'max_points',
        'total_points'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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
    
    /**
     * Get the user progress data
     *
     * @return UserProgress
     */
    public function getProgressData()
    {
        $rewards = [
            ['level' => 5, 'reward' => 'Thèmes et couleurs de l’interface ', 'unlocked' => true],
            ['level' => 10, 'reward' => 'Places pour concert exclusif', 'unlocked' => true],
            ['level' => 15, 'reward' => 'Premium 1 mois offert', 'unlocked' => true],
            ['level' => 20, 'reward' => 'Rencontre avec artiste', 'unlocked' => false],
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
        
        // Pour niveau 16 avec ~600 XP dans le niveau actuel:
        // - Le niveau 16 nécessite 16000 XP au total
        // - Le niveau 17 nécessite 17000 XP au total
        // - Donc pour niveau 16 avec 600/1000 XP dans le niveau actuel, 
        //   le total est de 15000 + 600 = 15600 XP
        
        $level = 16;
        $currentPoints = 700; // Points dans le niveau actuel
        $maxPoints = 1000;    // Points nécessaires pour passer au niveau suivant
        
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