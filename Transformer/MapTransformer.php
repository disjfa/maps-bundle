<?php

namespace Disjfa\MapsBundle\Transformer;

use Disjfa\MapsBundle\Entity\Map;
use Disjfa\MapsBundle\Security\MapVoter;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MapTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'markers',
    ];
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var MapMarkerTransformer
     */
    private $mapMarkerTransformer;

    /**
     * MapTransformer constructor.
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param RouterInterface $router
     * @param MapMarkerTransformer $mapMarkerTransformer
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        RouterInterface $router,
        MapMarkerTransformer $mapMarkerTransformer
    )
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
        $this->mapMarkerTransformer = $mapMarkerTransformer;
    }

    /**
     * @param Map $map
     * @return array
     */
    public function transform(Map $map)
    {
        $data = [
            'id' => $map->getId(),
            'title' => $map->getTitle(),
            'center_lat' => $map->getCenterLat(),
            'center_lng' => $map->getCenterLng(),
        ];

        $links = [];
        $links['get_url'] = $this->router->generate('disjfa_map_api_map_get', [
            'map' => $map->getId(),
        ]);
        if ($this->authorizationChecker->isGranted(MapVoter::PATCH, $map)) {
            $links['patch_url'] = $this->router->generate('disjfa_map_api_map_patch', [
                'map' => $map->getId(),
            ]);
            $links['create_marker_url'] = $this->router->generate('disjfa_map_api_map_marker_post', [
                'map' => $map->getId(),
            ]);
        }

        $data['links'] = $links;
        return $data;
    }


    /**
     * @param Map $map
     * @return Collection
     */
    public function includeMarkers(Map $map)
    {
        $markers = $map->getMarkers();

        return $this->collection($markers, $this->mapMarkerTransformer);
    }
}
