<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieListRepository")
 */
class MovieList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="movieLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFavorite;

    /**
     * @ORM\Column(type="array", nullable=false)
     */
    private $movies = [];

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $user): self
    {
        $this->userId = $user;

        return $this;
    }

    public function getIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function getMovies(): ?array
    {
        return $this->movies->toArray();
    }

    public function setMovies(?array $movies): self
    {
        $this->movies = $movies;

        return $this;
    }

    public function isMovieInList(int $movieId): bool
    {
        if ($this->movies->contains($movieId)) {
            return true;
        }
        return false;
    }

    public function addMovie(int $movieId): self
    {
        $this->movies->add($movieId);
        $this->movies = clone $this->movies; // trick

        return $this;
    }

    public function removeMovie(int $movieId): self
    {
        $this->movies->removeElement($movieId);
        $this->movies = clone $this->movies; // trick

        return $this;
    }

    public function __toString() 
    {
        return $this->name;
    }
}
