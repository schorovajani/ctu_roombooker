<?php

namespace App\Controller;

use App\Entity\Building;
use App\Service\BuildingService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class BuildingController extends AbstractFOSRestController
{
	private BuildingService $buildingService;

	/**
	 * @param BuildingService $buildingService
	 */
	public function __construct(BuildingService $buildingService)
	{
		$this->buildingService = $buildingService;
	}

	/**
	 * @Rest\Get("/buildings")
	 *
	 * @return Response
	 */
	public function routeGetBuildings(): Response
	{
		return $this->handleView($this->view($this->buildingService->getAll(), Response::HTTP_OK));
	}

	/**
	 * @Rest\Get("/buildings/{id}/rooms", requirements={"id":"\d+"})
	 *
	 * @param Building $building
	 * @return Response
	 */
	public function routeGetBuildingRooms(Building $building): Response
	{
		$view = $this->view($this->buildingService->getRooms($building), Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam']);
		return $this->handleView($view);
	}
}
