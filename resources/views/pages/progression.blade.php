@extends('layouts.app')

@section('title', 'Progression SPOT\'VIP')

@section('content')
<div class="progression-page">
    <div class="progression-header">
        <h1 class="progression-title">SPOT'VIP</h1>
        <p class="progression-subtitle">
            Gagnez des SPOINTS en écoutant de la musique, en créant des playlists et en interagissant avec les artistes. 
            Débloquez des récompenses exclusives en atteignant de nouveaux niveaux !
        </p>
    </div>

    <div class="level-info">
        <div class="big-level-badge">{{ $userProgress->getLevel() }}</div>
        <div class="level-details">
            <h2 class="current-level">Niveau {{ $userProgress->getLevel() }}</h2>
            <p class="points-info">
                {{ $userProgress->getCurrentPoints() }} / {{ $userProgress->getMaxPoints() }} SPOINTS 
                pour atteindre le niveau {{ $userProgress->getLevel() + 1 }}
            </p>
            <div class="spotvip-progress">
                <div class="spotvip-bar" style="width: {{ $userProgress->getProgressPercentage() }}%;"></div>
            </div>
        </div>
    </div>

    <div class="spotvip-rewards">
        <h2 class="rewards-title">Récompenses à débloquer</h2>
        <div class="rewards-timeline">
            <div class="timeline-line"></div>
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
                        
                        $pointsNeeded = $userProgress->getPointsForLevel($reward['level']);
                    @endphp
                    <div class="reward-item {{ !$reward['unlocked'] ? 'locked' : '' }}">
                        <div class="reward-level {{ $status }}">{{ $reward['level'] }}</div>
                        <div>
                            <div class="reward-name">{{ $reward['reward'] }}</div>
                            @if(!$reward['unlocked'])
                                <div class="reward-points">{{ $pointsNeeded }} SPOINTS restants</div>
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
                    <div class="earning-points">+25 à +50 SPOINTS par interaction</div>
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
</style>
@endpush