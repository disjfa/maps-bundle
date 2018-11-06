<?php

namespace Disjfa\MapsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use FOS\UserBundle\Model\UserInterface;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="map")
 */
class Map
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     * @ORM\Column(name="zoom", type="integer", nullable=true)
     */
    private $zoom;

    /**
     * @var int
     * @ORM\Column(name="center_lat", type="decimal", nullable=true)
     */
    private $centerLat;

    /**
     * @var int
     * @ORM\Column(name="center_lng", type="decimal", nullable=true)
     */
    private $centerLng;
    /**
     * @var string
     * @ORM\Column(name="user_id", type="string")
     */
    private $userId;
    /**
     * @var MapMarker[]
     * @ORM\OneToMany(targetEntity="Disjfa\MapsBundle\Entity\MapMarker", mappedBy="map")
     */
    private $markers;

    /**
     * @param UserInterface $user
     * @throws Exception
     */
    public function __construct(UserInterface $user)
    {
        $this->id = Uuid::uuid4();
        $this->userId = $user->getId();
        $this->markers = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getZoom(): int
    {
        return $this->zoom;
    }

    /**
     * @param int $zoom
     */
    public function setZoom(int $zoom): void
    {
        $this->zoom = $zoom;
    }

    /**
     * @return int
     */
    public function getCenterLat(): ?int
    {
        return $this->centerLat;
    }

    /**
     * @param int $centerLat
     */
    public function setCenterLat(int $centerLat): void
    {
        $this->centerLat = $centerLat;
    }

    /**
     * @return int
     */
    public function getCenterLng(): ?int
    {
        return $this->centerLng;
    }

    /**
     * @param int $centerLng
     */
    public function setCenterLng(int $centerLng): void
    {
        $this->centerLng = $centerLng;
    }

    /**
     * @return MapMarker[]|Collection
     */
    public function getMarkers(): Collection
    {
        return $this->markers;
    }

    /**
     * @param MapMarker[] $markers
     */
    public function setMarkers(array $markers): void
    {
        $this->markers = $markers;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
}
