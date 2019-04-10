<?php

namespace Disjfa\MapsBundle\Transformer;

use Disjfa\MapsBundle\Entity\MapMarker;
use Disjfa\MapsBundle\Security\MapMarkerVoter;
use League\Fractal\TransformerAbstract;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MapMarkerTransformer extends TransformerAbstract
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationCheker;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * MapTransformer constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationCheker
     * @param RouterInterface               $router
     */
    public function __construct(AuthorizationCheckerInterface $authorizationCheker, RouterInterface $router)
    {
        $this->authorizationCheker = $authorizationCheker;
        $this->router = $router;
    }

    public function transform(MapMarker $mapMarker)
    {
        $data = [
            'id' => $mapMarker->getId(),
            'title' => $mapMarker->getTitle(),
            'description' => $mapMarker->getDescription(),
            'center_lat' => $mapMarker->getCenterLat(),
            'center_lng' => $mapMarker->getCenterLng(),
        ];

        $links = [];
        if ($this->authorizationCheker->isGranted(MapMarkerVoter::PATCH, $mapMarker)) {
            $links['patch_url'] = $this->router->generate('disjfa_map_api_map_marker_patch', [
                'marker' => $mapMarker->getId(),
            ]);
        }

        $data['links'] = $links;

        return $data;
    }
}
