<?php

namespace App\Models;

class Track
{
    private $id;
    private $name;
    private $artist;
    private $artist_id;
    private $duration_ms;
    private $preview_url;
    private $image_url;
    private $album_name;
    private $album_id;

    public function __construct(
        string $id,
        string $name,
        string $artist,
        string $artist_id,
        int $duration_ms,
        ?string $preview_url = null,
        ?string $image_url = null,
        ?string $album_name = null,
        ?string $album_id = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->artist = $artist;
        $this->artist_id = $artist_id;
        $this->duration_ms = $duration_ms;
        $this->preview_url = $preview_url;
        $this->image_url = $image_url;
        $this->album_name = $album_name;
        $this->album_id = $album_id;
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

    public function getPreviewUrl()
    {
        return $this->preview_url;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function getAlbumName()
    {
        return $this->album_name;
    }

    public function getAlbumId()
    {
        return $this->album_id;
    }
}