<?php

namespace App\Models;

class Artist
{
    private $id;
    private $name;
    private $followers;
    private $popularity;
    private $genres;
    private $image_url;

    public function __construct(
        string $id,
        string $name,
        int $followers,
        int $popularity,
        array $genres = [],
        ?string $image_url = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->followers = $followers;
        $this->popularity = $popularity;
        $this->genres = $genres;
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

    public function getFollowers()
    {
        return $this->followers;
    }

    public function getFormattedFollowers()
    {
        if ($this->followers >= 1000000) {
            return round($this->followers / 1000000, 1) . 'M';
        } elseif ($this->followers >= 1000) {
            return round($this->followers / 1000, 1) . 'K';
        }
        
        return $this->followers;
    }

    public function getPopularity()
    {
        return $this->popularity;
    }

    public function getGenres()
    {
        return $this->genres;
    }

    private $imageUrl;

    public function setImageUrl($url)
    {
        $this->imageUrl = $url;
    }

    public function getImageUrl()
    {
        return $this->imageUrl ?? '/img/default-artist.jpg'; // Retourne une image par défaut si non définie
    }
}