<?php

namespace Disjfa\MapsBundle\Controller\Api;

use App\Form\Errors\Serializer;
use Disjfa\MapsBundle\Entity\Map;
use Disjfa\MapsBundle\Form\Type\EditMapType;
use Disjfa\MapsBundle\Security\MapVoter;
use Disjfa\MapsBundle\Transformer\MapTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/map")
 */
class MapController extends AbstractController
{
    /**
     * @Route("/{map}", name="disjfa_map_api_map_get")
     * @Method("GET")
     *
     * @param Map            $map
     * @param MapTransformer $mapTransformer
     *
     * @return Response
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
     *
     * @param Map     $map
     * @param Request $request
     *
     * @return Response
     */
    public function patchAction(Map $map, Request $request)
    {
        $this->denyAccessUnlessGranted(MapVoter::PATCH, $map);

        $form = $this->createForm(EditMapType::class, $map);
        $data = json_decode($request->getContent(), true);

        $form->submit($data['map']);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($map);
            $entityManager->flush();

            return new JsonResponse();
        }

        return new JsonResponse([
            'errors' => Serializer::serialize($form),
        ], 400);
    }
}
