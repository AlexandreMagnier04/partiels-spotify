<?php

namespace App\Models;

class TrackDetails
{
    private $id;
    private $name;
    private $artists;
    private $album_name;
    private $album_id;
    private $release_date;
    private $duration_ms;
    private $popularity;
    private $danceability;
    private $energy;
    private $tempo;
    private $key;
    private $mode;
    private $preview_url;
    private $image_url;

    public function __construct(
        string $id,
        string $name,
        array $artists,
        string $album_name,
        string $album_id,
        string $release_date,
        int $duration_ms,
        int $popularity,
        float $danceability,
        float $energy,
        float $tempo,
        int $key,
        int $mode,
        ?string $preview_url = null,
        ?string $image_url = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->artists = $artists;
        $this->album_name = $album_name;
        $this->album_id = $album_id;
        $this->release_date = $release_date;
        $this->duration_ms = $duration_ms;
        $this->popularity = $popularity;
        $this->danceability = $danceability;
        $this->energy = $energy;
        $this->tempo = $tempo;
        $this->key = $key;
        $this->mode = $mode;
        $this->preview_url = $preview_url;
        $this->image_url = $image_url;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getArtists()
    {
        return $this->artists;
    }

    public function getArtistsString()
    {
        return implode(', ', $this->artists);
    }

    public function getAlbumName()
    {
        return $this->album_name;
    }

    public function getAlbumId()
    {
        return $this->album_id;
    }

    public function getReleaseDate()
    {
        return $this->release_date;
    }

    public function getDuration()
    {
        return $this->duration_ms;
    }

    public function getFormattedDuration()
    {
        $minutes = floor($this->duration_ms / 60000);
        $seconds = floor(($this->duration_ms % 60000) / 1000);
        return $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
    }

    public function getPopularity()
    {
        return $this->popularity;
    }

    public function getDanceability()
    {
        return $this->danceability;
    }

    public function getEnergy()
    {
        return $this->energy;
    }

    public function getTempo()
    {
        return $this->tempo;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getKeyName()
    {
        $keys = [
            0 => 'C',
            1 => 'C♯/D♭',
            2 => 'D',
            3 => 'D♯/E♭',
            4 => 'E',
            5 => 'F',
            6 => 'F♯/G♭',
            7 => 'G',
            8 => 'G♯/A♭',
            9 => 'A',
            10 => 'A♯/B♭',
            11 => 'B'
        ];
        
        return $keys[$this->key] ?? 'Unknown';
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function getModeName()
    {
        return $this->mode === 1 ? 'Major' : 'Minor';
    }

    public function getPreviewUrl()
    {
        return $this->preview_url;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }
}