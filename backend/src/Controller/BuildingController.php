<?php

namespace App\Controller;

use App\Entity\Building;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class  BuildingController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
	/**
	 * @Rest\Get("/buildings")
	 * @return Response
	 */
	public function ActionGetBuildings(): Response
	{
		return $this->handleView($this->view($this->getDoctrine()->getRepository(Building::class)->findAll(), Response::HTTP_OK));
	}

	/**
	 * @Rest\Get("/buildings/{id}/rooms",requirements={"id":"\d+"})
	 * @param Building $building
	 * @return Response
	 */
	public function ActionGetBuildingRooms(Building $building): Response
	{
		return $this->handleView($this->view($building->getRooms(), Response::HTTP_OK));
	}
}


?>