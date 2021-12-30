<?php

namespace App\Controller;

use App\Entity\Building;
use App\Service\BuildingService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class  BuildingController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
	private BuildingService $buildingService;

	/**
	 * @param BuildingService $repo
	 */
	public function __construct(BuildingService $repo)
	{
		$this->buildingService = $repo;
	}

	/**
	 * @Rest\Get("/buildings")
	 * @return Response
	 */
	public function routeGetBuildings(): Response
	{
		return $this->handleView($this->view($this->buildingService->getAll(), Response::HTTP_OK));
	}

	/**
	 * @Rest\Get("/buildings/{id}/rooms",requirements={"id":"\d+"})
	 * @param Building $building
	 * @return Response
	 */
	public function routeGetBuildingRooms(Building $building): Response
	{
		return $this->handleView($this->view($building->getRooms(), Response::HTTP_OK));
	}
}
