<?php

namespace Disjfa\MapsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="map_marker")
 */
class MapMarker
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
     * @var float
     * @ORM\Column(name="center_lat", type="decimal", scale=12, precision=18, nullable=true)
     */
    private $centerLat;

    /**
     * @var float
     * @ORM\Column(name="center_lng", type="decimal", scale=12, precision=18, nullable=true)
     */
    private $centerLng;

    /**
     * @var Map
     * @ORM\ManyToOne(targetEntity="Disjfa\MapsBundle\Entity\Map", inversedBy="markers")
     */
    private $map;

    /**
     * @param Map $map
     * @throws Exception
     */
    public function __construct(Map $map)
    {
        $this->id = Uuid::uuid4();
        $this->map = $map;
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
     * @return float
     */
    public function getCenterLat(): ?float
    {
        return $this->centerLat;
    }

    /**
     * @param float $centerLat
     */
    public function setCenterLat(float $centerLat): void
    {
        $this->centerLat = $centerLat;
    }

    /**
     * @return float
     */
    public function getCenterLng(): ?float
    {
        return $this->centerLng;
    }

    /**
     * @param float $centerLng
     */
    public function setCenterLng(float $centerLng): void
    {
        $this->centerLng = $centerLng;
    }

    /**
     * @return Map
     */
    public function getMap(): Map
    {
        return $this->map;
    }

    /**
     * @param Map $map
     */
    public function setMap(Map $map): void
    {
        $this->map = $map;
    }
}
