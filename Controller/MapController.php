<?php

namespace Disjfa\MapsBundle\Controller;

use Disjfa\MapsBundle\Entity\Map;
use Disjfa\MapsBundle\Form\Type\MapType;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/map")
 */
class MapController extends AbstractController
{
    /**
     * @Route("/", name="disjfa_map_map_index")
     */
    public function index()
    {
        if ($this->getUser() instanceof UserInterface) {
            $maps = $this->getDoctrine()->getRepository(Map::class)->findBy([
                'userId' => $this->getUser()->getId(),
            ]);
        } else {
            $maps = [];
        }

        return $this->render('@DisjfaMaps/map/index.html.twig', [
            'maps' => $maps,
        ]);
    }

    /**
     * @Route("/create", name="disjfa_map_map_create")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $map = new Map($this->getUser());

        $form = $this->createForm(MapType::class, $map);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($map);
            $entityManager->flush();

            return $this->redirectToRoute('disjfa_map_map_show', [
                'map' => $map->getId(),
            ]);
        }

        return $this->render('@DisjfaMaps/map/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{map}", name="disjfa_map_map_show")
     * @param Map $map
     * @return Response
     */
    public function show(Map $map)
    {
        return $this->render('@DisjfaMaps/map/show.html.twig', [
            'map' => $map,
        ]);
    }
}
