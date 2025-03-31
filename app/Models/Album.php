<?php

namespace App\Models;

class Album
{
    private $id;
    private $name;
    private $artist;
    private $artist_id;
    private $release_date;
    private $total_tracks;
    private $image_url;
    private $tracks;
    private $popularity;
    private $genres;
    private $label;
    private $copyright;

    public function __construct(
        string $id,
        string $name,
        string $artist,
        string $artist_id,
        string $release_date,
        int $total_tracks,
        ?string $image_url = null,
        ?array $tracks = null,
        ?int $popularity = null,
        ?array $genres = null,
        ?string $label = null,
        ?string $copyright = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->artist = $artist;
        $this->artist_id = $artist_id;
        $this->release_date = $release_date;
        $this->total_tracks = $total_tracks;
        $this->image_url = $image_url;
        $this->tracks = $tracks;
        $this->popularity = $popularity;
        $this->genres = $genres ?? [];
        $this->label = $label;
        $this->copyright = $copyright;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function getArtistId()
    {
        return $this->artist_id;
    }

    public function getReleaseDate()
    {
        return $this->release_date;
    }

    public function getTotalTracks()
    {
        return $this->total_tracks;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function getTracks()
    {
        return $this->tracks;
    }

    public function getPopularity()
    {
        return $this->popularity;
    }

    public function getGenres()
    {
        return $this->genres;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getCopyright()
    {
        return $this->copyright;
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

        $minutes = floor($totalMs / 60000);
        $seconds = floor(($totalMs % 60000) / 1000);
        
        return $minutes . ' min ' . $seconds . ' sec';
    }
}