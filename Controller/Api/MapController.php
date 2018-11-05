<?php

namespace Disjfa\MapsBundle\Controller\Api;

use Disjfa\MapsBundle\Entity\Map;
use Disjfa\MapsBundle\Transformer\MapTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/map")
 */
class MapController extends AbstractController
{
    /**
     * @Route("/{map}", name="disjfa_map_api_map_get")
     * @Method("GET")
     * @param Map $map
     * @param MapTransformer $mapTransformer
     * @return JsonResponse
     */
    public function getAction(Map $map, MapTransformer $mapTransformer)
    {
        $item = new Item($map, $mapTransformer);
        $manager = new Manager();
        return new JsonResponse($manager->createData($item)->toArray());
    }

    /**
     * @Route("/{map}", name="disjfa_map_api_map_patch")
     * @Method("PATCH")
     * @param Map $map
     * @return void
     */
    public function patchAction(Map $map)
    {
        dump($map);
    }
}
