@extends('layouts.app')

@section('title', 'Progression SPOT\'VIP')

@section('content')
<div class="progression-page {{ strtolower($userProgress->getCurrentTier()) }}-tier">
    <div class="progression-header">
        <h1 class="progression-title">SPOT'VIP</h1>
        <p class="progression-subtitle">
            Gagnez des SPOINTS en écoutant de la musique, en créant des playlists et en interagissant avec les artistes. 
            Débloquez des récompenses exclusives en atteignant de nouveaux paliers !
        </p>
    </div>

    <div class="level-info">
        <div class="big-level-badge">{{ $userProgress->getLevel() }}</div>
        <div class="level-details">
            <h2 class="current-level">Niveau {{ $userProgress->getLevel() }} - Palier {{ $userProgress->getCurrentTier() }}</h2>
            <p class="points-info">
                {{ $userProgress->getTotalPoints() }} / {{ $userProgress->getPointsForLevel($userProgress->getLevel()) }} SPOINTS 
                pour atteindre le niveau {{ $userProgress->getLevel() + 1 }}
            </p>
            <div class="spotvip-progress">
                <div class="spotvip-bar" style="width: {{ $userProgress->getProgressPercentage() }}%;"></div>
            </div>
        </div>
    </div>
    
    <!-- Progression entre paliers -->
    <div class="tier-progression">
        <h2 class="tier-title">Progression vers le prochain palier</h2>
        <div class="tier-details">
            <div class="tier-info">
                <div class="current-tier">{{ $userProgress->getCurrentTier() }}</div>
                <span class="tier-arrow">→</span>
                <div class="next-tier" style="background-color: rgba{{ str_replace('#', '(', $userProgress->getTierColor($userProgress->getNextTier())) }}, 0.6);">
                    {{ $userProgress->getNextTier() }}
                </div>
                <span class="tier-points">{{ number_format($userProgress->getPointsToNextTier()) }} SPOINTS restants</span>
            </div>
            <div class="tier-progress-bar">
                <div class="tier-progress-fill" style="width: {{ $userProgress->getTierProgressPercentage() }}%;"></div>
            </div>
        </div>
    </div>

    <!-- Avantages du palier actuel -->
    <div class="current-tier-rewards">
        <h2 class="rewards-title">Avantages de votre palier {{ $userProgress->getCurrentTier() }}</h2>
        <div class="tier-rewards-list">
            @foreach($userProgress->getCurrentTierRewards() as $reward)
                <div class="tier-reward-item">
                    <div class="tier-reward-icon" style="background-color: rgba{{ str_replace('#', '(', $userProgress->getTierColor($userProgress->getCurrentTier())) }}, 0.2);">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="tier-reward-name">{{ $reward }}</div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Aperçu du prochain palier -->
    @if($userProgress->getCurrentTier() !== $userProgress->getNextTier())
    <div class="next-tier-rewards">
        <h2 class="rewards-title">Prochain palier : {{ $userProgress->getNextTier() }}</h2>
        <p class="next-tier-info">Débloquez ces avantages en atteignant {{ $userProgress->getPointsToNextTier() }} SPOINTS supplémentaires</p>
        <div class="tier-rewards-list next">
            @foreach($userProgress->getNextTierRewards() as $reward)
                <div class="tier-reward-item locked">
                    <div class="tier-reward-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="tier-reward-name">{{ $reward }}</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="spotvip-rewards">
        <h2 class="rewards-title">Récompenses des niveaux</h2>
        <div class="rewards-timeline">
            <div class="timeline-line"></div>
            <div class="rewards-progress-bar">
                <div class="rewards-progress-fill" style="width: {{ $userProgress->getRewardsProgressPercentage() }}%;"></div>
            </div>
            <div class="rewards-list">
                @foreach($userProgress->getRewards() as $reward)
                    @php
                        $status = '';
                        if ($reward['unlocked']) {
                            $status = 'unlocked';
                        } else if ($reward['level'] == $userProgress->getLevel() + 1) {
                            $status = 'current';
                        } else {
                            $status = 'locked';
                        }
                        
                        $pointsNeeded = $userProgress->getPointsForLevel($reward['level']) - $userProgress->getTotalPoints();
                        $pointsNeeded = max(0, $pointsNeeded);
                    @endphp
                    <div class="reward-item {{ !$reward['unlocked'] ? 'locked' : '' }}">
                        <div class="reward-level {{ $status }}" style="color: var(--spotify-black); {{ $reward['unlocked'] ? 'background-color: '.$userProgress->getTierColor($userProgress->getCurrentTier()).';' : '' }}">
                            {{ $reward['level'] }}
                        </div>
                        <div>
                            <div class="reward-name">{{ $reward['reward'] }}</div>
                            @if(!$reward['unlocked'])
                                <div class="reward-points">{{ number_format($pointsNeeded) }} SPOINTS restants</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="earning-points">
        <h2 class="earning-title">Comment gagner des SPOINTS</h2>
        <div class="earning-list">
            <div class="earning-item">
                <div class="earning-icon">
                    <i class="fas fa-headphones"></i>
                </div>
                <div class="earning-info">
                    <div class="earning-action">Écouter de la musique</div>
                    <div class="earning-points">+1 SPOINT par heure d'écoute</div>
                </div>
            </div>
            <div class="earning-item">
                <div class="earning-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="earning-info">
                    <div class="earning-action">Créer et partager des playlists</div>
                    <div class="earning-points">+50 SPOINTS par playlist</div>
                </div>
            </div>
            <div class="earning-item">
                <div class="earning-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="earning-info">
                    <div class="earning-action">Écouter des albums en avant-première</div>
                    <div class="earning-points">+100 SPOINTS</div>
                </div>
            </div>
            <div class="earning-item">
                <div class="earning-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="earning-info">
                    <div class="earning-action">Participer à des événements Spotify</div>
                    <div class="earning-points">+200 SPOINTS</div>
                </div>
            </div>
            <div class="earning-item">
                <div class="earning-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="earning-info">
                    <div class="earning-action">Interagir avec les artistes</div>
                    <div class="earning-points">+25 à +75 SPOINTS par interaction</div>
                </div>
            </div>
        </div>
    </div>

    <div class="activity-history">
        <h2 class="rewards-title">Activité récente</h2>
        @if(count($userProgress->getHistory()) > 0)
            <div class="history-list">
                @foreach($userProgress->getHistory() as $item)
                <div class="history-item">
                    <div class="history-date">{{ date('d/m/Y H:i', $item['timestamp']) }}</div>
                    <div class="history-action">{{ $item['action'] }}</div>
                    <div class="history-points">+{{ $item['points'] }} SPOINTS</div>
                </div>
                @endforeach
            </div>
        @else
            <p class="no-history">Aucune activité récente à afficher. Commencez à écouter de la musique et à interagir avec la plateforme pour gagner des SPOINTS !</p>
        @endif
    </div>
</div>
@endsection


@push('styles')
<style>
    .activity-history {
        margin-bottom: 50px;
    }
    
    .history-list {
        background-color: var(--spotify-dark-gray);
        border-radius: 8px;
        overflow: hidden;
    }
    
    .history-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .history-item:last-child {
        border-bottom: none;
    }
    
    .history-date {
        width: 180px;
        font-size: 14px;
        color: var(--spotify-off-white);
    }
    
    .history-action {
        flex: 1;
        font-weight: 500;
    }
    
    .history-points {
        font-weight: 600;
        color: var(--spotify-green);
    }
    
    .no-history {
        color: var(--spotify-off-white);
        text-align: center;
        padding: 30px;
        background-color: var(--spotify-dark-gray);
        border-radius: 8px;
    }
    
    /* Styles pour la barre de progression des récompenses */
    .rewards-progress-bar {
        position: absolute;
        top: 24px;
        left: 0;
        width: 100%;
        height: 4px;
        background-color: rgba(255, 255, 255, 0.2);
        z-index: 1;
        overflow: hidden;
    }
    
    .rewards-progress-fill {
        height: 100%;
        background-color: var(--spotify-green);
        z-index: 2;
        transition: width 0.3s ease;
    }

</style>
@endpush