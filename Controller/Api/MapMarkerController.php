<?php

namespace Disjfa\MapsBundle\Controller\Api;

use App\Form\Errors\Serializer;
use Disjfa\MapsBundle\Entity\Map;
use Disjfa\MapsBundle\Entity\MapMarker;
use Disjfa\MapsBundle\Form\Type\MapMarkerType;
use Disjfa\MapsBundle\Security\MapVoter;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/map-marker")
 */
class MapMarkerController extends AbstractController
{
    /**
     * @Route("/{map}", name="disjfa_map_api_map_marker_post")
     * @Method("POST")
     *
     * @param Map     $map
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function post(Map $map, Request $request)
    {
        $this->denyAccessUnlessGranted(MapVoter::PATCH, $map);

        $mapMarker = new MapMarker($map);
        $form = $this->createForm(MapMarkerType::class, $mapMarker);
        $data = json_decode($request->getContent(), true);

        $form->submit($data['map_marker']);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mapMarker);
            $entityManager->flush();

            return new JsonResponse();
        }

        return new JsonResponse([
            'errors' => Serializer::serialize($form),
        ], 400);
    }

    /**
     * @Route("/{marker}", name="disjfa_map_api_map_marker_patch")
     * @Method("PATCH")
     *
     * @param MapMarker $marker
     * @param Request   $request
     *
     * @return Response
     */
    public function patch(MapMarker $marker, Request $request)
    {
        $form = $this->createForm(MapMarkerType::class, $marker);
        $data = json_decode($request->getContent(), true);
        $form->submit($data['map_marker']);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($marker);
            $entityManager->flush();

            return new JsonResponse();
        }

        return new JsonResponse([
            'errors' => Serializer::serialize($form),
        ], 400);
    }
}
