<?php

namespace App\Models;

class Playlist
{
    private $id;
    private $name;
    private $description;
    private $owner;
    private $image_url;
    private $tracks_count;
    private $tracks;
    private $covers;

    public function __construct(
        string $id,
        string $name,
        string $description,
        string $owner,
        ?string $image_url = null,
        int $tracks_count = 0,
        ?array $covers = null,
        ?array $tracks = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->owner = $owner;
        $this->image_url = $image_url;
        $this->tracks_count = $tracks_count;
        $this->tracks = $tracks;
        $this->covers = $covers ?? [$image_url]; // Si pas de covers spécifiques, utiliser l'image principale
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function getTracksCount()
    {
        return $this->tracks_count;
    }

    public function getTracks()
    {
        return $this->tracks;
    }

    public function getCovers()
    {
        return $this->covers;
    }

    public function getFormattedDuration()
    {
        if (!$this->tracks) {
            return 'Unknown';
        }

        $totalMs = 0;
        foreach ($this->tracks as $track) {
            $totalMs += $track->getDuration();
        }

        $hours = floor($totalMs / 3600000);
        $minutes = floor(($totalMs % 3600000) / 60000);
        
        if ($hours > 0) {
            return $hours . ' hr ' . $minutes . ' min';
        }
        
        return $minutes . ' min';
    }
}